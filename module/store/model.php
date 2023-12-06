<?php
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
     * Get app list from cloud market.
     *
     * @param  string $keyword
     * @param  array  $categories
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return object
     */
    public function searchApps($sortBy = '', $keyword = '', $categories = array(), $page = 1, $pageSize = 20)
    {
        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/applist?channel='. $this->config->cloud->api->channel;
        $apiUrl .= "&q=" . rawurlencode(trim($keyword));
        $apiUrl .= "&sort=" . rawurlencode(trim($sortBy));
        $apiUrl .= "&page=$page";
        $apiUrl .= "&page_size=$pageSize";
        foreach($categories as $category) $apiUrl .= "&category=$category"; // The names of category are same that reason is CNE api is required.

        $result = commonModel::apiGet($apiUrl, array(), $this->config->cloud->api->headers);
        if($result->code == 200) return $result->data;

        $pagedApps = new stdclass;
        $pagedApps->apps  = array();
        $pagedApps->total = 0;
        return $pagedApps;
    }

    /**
     * Get app info by App chart from cloud market.
     *
      @param  string $chart
     * @param  string $channel
     * @param  bool   $analysis
     * @param  string $version
     * @access public
     * @return object|null
     */
    public function getAppInfoByChart($chart, $channel, $analysis, $version = '')
    {
        return $this->getAppInfo(0, $analysis, $chart, $version,  $channel);
    }

    /**
     * Get app info from cloud market.
     *
     * @param  int     $id
     * @param  boolean $analysis true: log this request for analysis.
     * @param  string  $name
     * @param  string  $version
     * @param  string  $channel
     * @access public
     * @return object|null
     */
    public function getAppInfo($id, $analysis = false, $name = '', $version ='',  $channel = '')
    {
        if(empty($id)) return null;
        $apiParams = array();
        $apiParams['analysis'] = $analysis ? 'true' : 'false' ;

        if($id)      $apiParams['id']      = $id;
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
     * Get app infos map by name array from cloud market.
     * 根据名称查询多个应用信息。
     *
     * @param  array  $nameList
     * @access public
     * @return object|null
     */
    public function getAppMapByNames($nameList = array(),  $channel = 'stable')
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
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getVersionPairs(int $id): array
    {
        $pairs    = array();
        $versions = $this->appVersionList($id);

        foreach($versions as $version) $pairs[$version->version] = $version->app_version . '-' . $version->version;

        return $pairs;
    }

    /**
     * Get app version list to install.
     *
     * @param  int    $id
     * @param  string $name
     * @param  string $channel
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return mixed
     */
    public function appVersionList($id, $name = '', $channel = '', $page = 1, $pageSize = 3)
    {
        $apiParams = array();
        $apiParams['page']      = $page;
        $apiParams['page_size'] = $pageSize;

        if($id)      $apiParams['id']      = $id;
        if($name)    $apiParams['name']    = $name;
        if($channel) $apiParams['channel'] = $channel;

        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/app/version';
        $result  = commonModel::apiGet($apiUrl, $apiParams, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return array();

        return array_combine(helper::arrayColumn($result->data, 'version'), $result->data);
    }

    /**
     * Get upgradable versions of app from cloud market.
     *
     * @param  string $currentVersion
     * @param  int    $appID          appID is required if no appName.
     * @param  string $appName        appName is required if no appID.
     * @param  string $channel
     * @access public
     * @return mixed
     */
    public function getUpgradableVersions($currentVersion, $appID = 0, $appName = '', $channel = '')
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

        $result  = commonModel::apiGet($apiUrl, $conditions, $this->config->cloud->api->headers);
        if(!isset($result->code) || $result->code != 200) return array();

        return $result->data;
    }

    /**
     * Get the latest version of QuCheng platform.
     *
     * @access public
     * @return object
     */
    public function platformLatestVersion()
    {
        $versionList = $this->getUpgradableVersions($this->config->platformVersion, 0, $this->config->edition == 'open' ? 'qucheng' : 'qucheng-biz', $this->config->cloud->api->channel);

        $latestVersion = $this->pickHighestVersion($versionList);
        if(!empty($latestVersion) && version_compare(str_replace('-', '.', $latestVersion->version), str_replace('-', '.', $this->config->platformVersion), '>')) return $latestVersion;

        $latestVersion = new stdclass;
        $latestVersion->app_version = getenv('APP_VERSION');
        $latestVersion->version     = $this->config->platformVersion;

        return $latestVersion;
    }

    /**
     * Get the latest versions of app from cloud market.
     *
     * @param  int    $appID
     * @param  string $currentVersion
     * @access public
     * @return object|null
     */
    public function appLatestVersion($appID, $currentVersion)
    {
        $versionList = $this->getUpgradableVersions($currentVersion, $appID);

        $latestVersion = $this->pickHighestVersion($versionList);
        if(empty($latestVersion)) return null;

        if(version_compare(str_replace('-', '.', $latestVersion->version), str_replace('-', '.', $currentVersion), '>')) return $latestVersion;

        return null;
    }

    /**
     * Pick highest version from version list and compared version.
     *
     * @param  array       $versionList
     * @access private
     * @return object|null
     */
    private function pickHighestVersion($versionList)
    {
        if(empty($versionList)) return null;

        $highestVersion = new stdclass;
        $highestVersion->version = '0.0.0';
        foreach($versionList as $version)
        {
            if(version_compare(str_replace('-', '.', $version->version), str_replace('-', '.', $highestVersion->version), '>')) $highestVersion = $version;
        }

        return $highestVersion;
    }

    /**
     * Get app setting from cloud market.
     *
     * @param  int $id
     * @access public
     * @return array
     */
    public function getAppSettings($id)
    {
        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/appsettings';
        $result  = commonModel::apiGet($apiUrl, array('id' => $id), $this->config->cloud->api->headers);
        if($result->code != 200) return array();

        /* Convert "." to "_" */
        $components = $result->data->components;
        foreach($result->data->components as &$component)
        {
            foreach($component->settings as $setting) $setting->field = str_replace('.', '_', $setting->field);
        }

        return $components;
    }

    /**
     * Get category list from cloud market.
     *
     * @access public
     * @return object
     */
    public function getCategories()
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
     * Get switcher of browse page of store.
     *
     * @access public
     * @return string
     */
    public function getBrowseSwitcher()
    {
        $title = $this->lang->store->cloudStore;

        if($this->config->cloud->api->switchChannel) $title .= '（' . ($this->config->cloud->api->channel == 'stable' ? $this->lang->store->stableChannel : $this->lang->store->testChannel) . '）';

        $output = "<div class='btn-group header-btn'>";
        if($this->config->cloud->api->switchChannel)
        {
            $stableActive = $this->config->cloud->api->channel == 'stable' ? 'active' : '';
            $testActive   = $this->config->cloud->api->channel != 'stable' ? 'active' : '';

            $output .= "<a href='javascript:;' class='btn'  data-toggle='dropdown'>{$title}<span class='caret' style='margin-bottom: -1px;margin-left:5px;'></span></a>";
            $output .= "<ul class='dropdown-menu'>";
            $output .= "<li class='{$stableActive}'>" . html::a(helper::createLink('store', 'browse', 'sortType=update_time&perPage=20&pageID=1&channel=stable'), $this->lang->store->stableChannel) ."</li>";
            $output .= "<li class='{$testActive}'>" . html::a(helper::createLink('store', 'browse', 'sortType=update_time&perPage=20&pageID=1&channel=test'), $this->lang->store->testChannel) ."</li>";
            $output .= "</ul>";
        }
        else
        {
            $output .= "<a href='javascript:;' class='btn'  data-toggle='dropdown'>{$title}</a>";
        }

        $output .= "</div>";

        return $output;
    }

    /**
     * Get switcher of app view page of store.
     *
     * @param  object $app
     * @access public
     * @return string
     */
    public function getAppViewSwitcher($app)
    {
        $output  = $this->getBrowseSwitcher();
        $output .= "<div class='btn-group header-btn'>";
        $output .= html::a(helper::createLink('store', 'appview', "id=$app->id"), $app->alias, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get app dynamic news from Qucheng offical site.
     *
     * @param  object $cloudApp
     * @param  int    $pageID
     * @param  int    $recPerPage
     * @access public
     * @return object|null
     */
    public function appDynamic($cloudApp, $pageID = 1, $recPerPage = 20)
    {
        $alias = strtolower(str_replace('-', '', $cloudApp->chart));
        $url   = $this->config->store->quchengSiteHost . "/article-apibrowse-{$alias}-{$pageID}-{$recPerPage}.html";

        $result = commonModel::apiGet($url);
        if($result && $result->code == 200) return $result->data;

        return null;
    }

    /**
     * Get solution list.
     *
     * @param  string $sortBy    possible vlues: id,name,create_time,update_time
     * @param  string $keyword
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return object
     */
    public function searchSolutions($sortBy = '', $keyword = '', $page = 1, $pageSize = 20)
    {
        $apiUrl  = $this->config->cloud->api->host;
        $apiUrl .= '/api/market/solution/list?channel='. $this->config->cloud->api->channel;
        $apiUrl .= "&sort=" . rawurlencode(trim($sortBy));
        $apiUrl .= "&q=" . rawurlencode(trim($keyword));
        $apiUrl .= "&page=$page";
        $apiUrl .= "&page_size=$pageSize";

        $result = commonModel::apiGet($apiUrl, array(), $this->config->cloud->api->headers);
        if($result->code == 200) return $result->data;

        $pagedApps = new stdclass;
        $pagedApps->apps  = array();
        $pagedApps->total = 0;
        return $pagedApps;
    }

    /**
     * Get solution info.
     *
     * @param  string     $type
     * @param  int|string $value
     * @access public
     * @return object
     */
    public function getSolution($type, $value)
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
     * Get solution config.
     *
     * @param  string     $type
     * @param  int|string $value
     * @access public
     * @return object
     */
    public function solutionConfig($type, $value)
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
