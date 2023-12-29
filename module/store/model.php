<?php
declare(strict_types=1);
/**
 * The model file of store module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjanhua@easycorp.ltd>
 * @package   store
 * @version   $Id$
 * @link      https://www.zentao.net
 */
class storeModel extends model
{
    /**
     * Construct function: set api headers.
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($appName = '')
    {
        parent::__construct($appName);

        global $config, $app;
        $config->cloud->api->headers[] = "{$config->cloud->api->auth}: {$config->cloud->api->token}";

        if($config->cloud->api->switchChannel && $app->session->cloudChannel) $config->cloud->api->channel = $app->session->cloudChannel;
    }

    /**
     * 根据关键字查询应用市场应用列表。
     * Get app list from cloud market.
     *
     * @param  string $orderBy
     * @param  string $keyword
     * @param  array  $categories
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return object
     */
    public function searchApps(string $orderBy = '', string $keyword = '', array $categories = array(), int $page = 1, int $pageSize = 20): object
    {
        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/applist?channel='. $this->config->cloud->api->channel;
        $apiUrl .= "&q=" . rawurlencode(trim($keyword));
        $apiUrl .= "&sort=" . rawurlencode(trim($orderBy));
        $apiUrl .= "&page=$page";
        $apiUrl .= "&page_size=$pageSize";
        foreach($categories as $category) $apiUrl .= "&category=$category"; // The names of category are same that reason is CNE api is required.

        $result = commonModel::apiGet($apiUrl, array(), $this->config->cloud->api->headers);
        if($result->code == 200) return $result->data;

        $pagedApps = new stdclass();
        $pagedApps->apps  = array();
        $pagedApps->total = 0;
        return $pagedApps;
    }

    /**
     * 通过接口获取应用详情。
     * Get app info from cloud market.
     *
     * @param  int     $appID
     * @param  boolean $analysis true: log this request for analysis.
     * @param  string  $name
     * @param  string  $version
     * @param  string  $channel
     * @access public
     * @return object|null
     */
    public function getAppInfo(int $appID, bool $analysis = false, string $name = '', string $version ='', string $channel = ''): object|null
    {
        if(empty($appID)) return null;
        $apiParams = array();
        $apiParams['analysis'] = $analysis ? 'true' : 'false' ;

        if($appID)   $apiParams['id']      = $appID;
        if($name)    $apiParams['name']    = $name;
        if($version) $apiParams['version'] = $version;
        if($channel) $apiParams['channel'] = $channel;

        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/appinfo';
        $result  = commonModel::apiGet($apiUrl, $apiParams, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return null;

        return $result->data;
    }

    /**
     * 根据名称查询多个应用信息。
     * Get app infos map by name array from cloud market.
     *
     * @param  array  $nameList
     * @access public
     * @return object|null
     */
    public function getAppMapByNames(array $nameList = array(), string $channel = 'stable'): object|null
    {
        $apiParams = array('name_list' => $nameList, 'channel' => $channel);

        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/app_info_list';
        $result  = commonModel::apiPost($apiUrl, $apiParams, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return null;

        return $result->data;
    }

    /**
     * Get app version pairs by id.
     *
     * @param  int    $appID
     * @access public
     * @return array
     */
    public function getVersionPairs(int $appID): array
    {
        $pairs    = array();
        $versions = $this->appVersionList($appID);

        foreach($versions as $version) $pairs[$version->version] = $version->app_version . '-' . $version->version;

        return $pairs;
    }

    /**
     * 获取应用的可安装版本。
     * Get app version list to install.
     *
     * @param  int    $appID
     * @param  string $name
     * @param  string $channel
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return array
     */
    public function appVersionList(int $appID, string $name = '', string $channel = '', int $page = 1, int $pageSize = 3): array
    {
        $apiParams = array();
        $apiParams['page']      = $page;
        $apiParams['page_size'] = $pageSize;

        if($appID)   $apiParams['id']      = $appID;
        if($name)    $apiParams['name']    = $name;
        if($channel) $apiParams['channel'] = $channel;

        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/app/version';
        $result  = commonModel::apiGet($apiUrl, $apiParams, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return array();

        return array_combine(helper::arrayColumn($result->data, 'version'), $result->data);
    }

    /**
     * 获取应用可以升级到的版本。
     * Get upgradable versions of app from cloud market.
     *
     * @param  string $currentVersion
     * @param  int    $appID          appID is required if no appName.
     * @param  string $appName        appName is required if no appID.
     * @param  string $channel
     * @access public
     * @return array
     */
    public function getUpgradableVersions(string $currentVersion, int $appID = 0, string $appName = '', string $channel = ''): array
    {
        $channel = $channel ? $channel : $this->config->cloud->api->channel;
        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/app/version/upgradable';

        $conditions = array('version' => $currentVersion, 'channel' => $channel);
        if($appID)
        {
            $conditions['id'] = $appID;
        }
        else
        {
            $conditions['name'] = $appName;
        }

        $result = commonModel::apiGet($apiUrl, $conditions, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return array();

        return $result->data;
    }

    /**
     * 获取应用的最新版本。
     * Get the latest versions of app from cloud market.
     *
     * @param  int    $appID
     * @param  string $currentVersion
     * @access public
     * @return object|null
     */
    public function appLatestVersion(int $appID, string $currentVersion): object|null
    {
        $versionList = $this->getUpgradableVersions($currentVersion, $appID);

        $latestVersion = $this->pickHighestVersion($versionList);
        if(empty($latestVersion)) return null;

        if(version_compare(str_replace('-', '.', $latestVersion->version), str_replace('-', '.', $currentVersion), '>')) return $latestVersion;

        return null;
    }

    /**
     * 从版本列表中选择最高版本并进行比较。
     * Pick highest version from version list and compared version.
     *
     * @param  array       $versionList
     * @access private
     * @return object|null
     */
    private function pickHighestVersion(array $versionList): object|null
    {
        if(empty($versionList)) return null;

        $highestVersion = new stdclass();
        $highestVersion->version = '0.0.0';
        foreach($versionList as $version)
        {
            if(version_compare(str_replace('-', '.', $version->version), str_replace('-', '.', $highestVersion->version), '>')) $highestVersion = $version;
        }

        return $highestVersion;
    }

    /**
     * 从云市场获取类别列表。
     * Get category list from cloud market.
     *
     * @access public
     * @return object
     */
    public function getCategories(): object
    {
        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/categories';
        $result  = commonModel::apiGet($apiUrl, array(), $this->config->cloud->api->headers);
        if($result->code == 200) return $result->data;

        $categories = new stdclass;
        $categories->categories = array();
        $categories->total      = 0;
        return $categories;
    }

    /**
     * 从渠成获取应用动态消息。
     * Get app dynamic news from Qucheng offical site.
     *
     * @param  object $cloudApp
     * @param  int    $pageID
     * @param  int    $recPerPage
     * @access public
     * @return object|null
     */
    public function appDynamic(object $cloudApp, int $pageID = 1, int $recPerPage = 20): object|null
    {
        $alias = strtolower(str_replace('-', '', $cloudApp->chart));
        $url   = $this->config->store->quchengSiteHost . "/article-apibrowse-{$alias}-{$pageID}-{$recPerPage}.html";

        $result = commonModel::apiGet($url);
        if($result && $result->code == 200) return $result->data;

        return null;
    }

    /**
     * 根据参数获取解决方案信息。
     * Get solution info.
     *
     * @param  string     $type
     * @param  int|string $value
     * @access public
     * @return object
     */
    public function getSolution(string $type, string $value): object
    {
        $apiParams = array();
        $apiParams[$type] = $value;

        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/solution/info?channel='. $this->config->cloud->api->channel;
        $result  = commonModel::apiGet($apiUrl, $apiParams, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return new stdclass();

        $solution = $result->data;
        $solution->apps = array_combine(helper::arrayColumn($solution->apps, 'chart'), $solution->apps);

        return $solution;
    }

    /**
     * 获取解决方案的配置信息。
     * Get solution config.
     *
     * @param  string     $type
     * @param  int|string $value
     * @access public
     * @return object
     */
    public function solutionConfig(string $type, string $value): object
    {
        $apiParams = array();
        $apiParams[$type] = $value;

        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/solution/schema?channel='. $this->config->cloud->api->channel;
        $result  = commonModel::apiGet($apiUrl, $apiParams, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return new stdclass();

        return $result->data;
    }
}
