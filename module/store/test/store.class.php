<?php
declare(strict_types = 1);
class storeTest
{
    private $objectModel;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('store');

        $this->objectModel->config->inQuickon           = true;
        $this->objectModel->config->k8space             = 'quickon-system';
        $this->objectModel->config->CNE->api->host      = 'http://10.0.7.210:32380';
        $this->objectModel->config->CNE->api->token     = 'JMz7HCoQ3WHoYbpNyYNpvMfHqde9ugtV';
        $this->objectModel->config->CNE->app->domain    = 'dops.corp.cc';

        $this->objectModel->config->cloud->api->host          = 'http://api.qucheng.com';
        $this->objectModel->config->cloud->api->channel       = 'stable';
        $this->objectModel->config->cloud->api->auth          = 'X-Auth-Token';
        $this->objectModel->config->cloud->api->token         = '';
        $this->objectModel->config->cloud->api->switchChannel = false;
    }

    /**
     * 魔术方法，调用store模型中的方法。
     * Magic method, call the method in the store model.
     *
     * @param  string $name
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->objectModel->$name(...$arguments);
    }

    /**
     * 测试根据关键字查询应用市场应用列表。
     * Test get app list from cloud market.
     *
     * @param  string $orderBy
     * @param  string $keyword
     * @param  array  $categories
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return array|string
     */
    public function searchAppsTest(string $orderBy = '', string $keyword = '', array $categories = array(), int $page = 1, int $pageSize = 20): array|string|int
    {
         $searchResult = $this->searchApps($orderBy, $keyword, $categories, $page, $pageSize);

         if(!empty($keyword) && !empty($searchResult->total))
         {
             $firstData = $searchResult->apps[0];
             return strpos($firstData->alias, $keyword) !== false ? 'Success' : 'Fail';
         }

         if($pageSize != 20 && !empty($searchResult->total)) return count($searchResult->apps);

         return empty($searchResult->total) ? 'No data!' : 'Success';
    }

    /**
     * 测试通过接口获取应用详情。
     * Test get app info from cloud market.
     *
     * @param  int     $appID
     * @param  boolean $analysis true: log this request for analysis.
     * @param  string  $name
     * @param  string  $version
     * @param  string  $channel
     * @access public
     * @return object|null
     */
    public function getAppInfoTest(int $appID, bool $analysis = false, string $name = '', string $version ='', string $channel = ''): object|null
    {
        return $this->getAppInfo($appID, $analysis, $name, $version, $channel);
    }

    /**
     * 测试根据名称查询多个应用信息。
     * Test get app infos map by name array from cloud market.
     *
     * @param  array   $nameList
     * @param  string  $channel
     * @access public
     * @return object|null
     */
    public function getAppMapByNamesTest(array $nameList = array()): object|null
    {
        $result = $this->getAppMapByNames($nameList);
        return empty((array)$result) ? null : $result;
    }

    /**
     * 测试获取应用的可安装版本。
     * Test get app version list to install.
     *
     * @param  int    $appID
     * @param  string $name
     * @param  string $channel
     * @param  int    $page
     * @param  int    $pageSize
     * @access public
     * @return array|int
     */
    public function appVersionListTest(int $appID, string $name = '', string $channel = '', int $page = 1, int $pageSize = 3): array|int
    {
        $versionList = $this->appVersionList($appID, $name, $channel, $page, $pageSize);
        if($pageSize != 3 && !empty($versionList)) return count($versionList);
        return $versionList;
    }

    /**
     * 测试获取版本键值对。
     * Test get app version pairs by id.
     *
     * @param  int    $appID
     * @access public
     * @return array
     */
    public function getVersionPairsTest(int $appID): array
    {
        return $this->getVersionPairs($appID);
    }

    /**
     * 测试获取应用可以升级到的版本。
     * Test get upgradable versions of app from cloud market.
     *
     * @param  string $currentVersion
     * @param  int    $appID          appID is required if no appName.
     * @param  string $appName        appName is required if no appID.
     * @access public
     * @return array
     */
    public function getUpgradableVersionsTest(string $currentVersion, int $appID = 0, string $appName = ''): array
    {
        return $this->getUpgradableVersions($currentVersion, $appID, $appName);
    }

    /**
     * 测试获取应用的最新版本。
     * Test get the latest versions of app from cloud market.
     *
     * @param  int    $appID
     * @param  string $currentVersion
     * @access public
     * @return object|null
     */
    public function appLatestVersionTest(int $appID, string $currentVersion): object|null
    {
        return $this->appLatestVersion($appID, $currentVersion);
    }

    /**
     * 测试获取应用市场应用的配置。
     * Test get app setting from cloud market.
     *
     * @param  int $appID
     * @access public
     * @return array
     */
    public function getAppSettingsTest(int $appID): array
    {
        return $this->getAppSettings($appID);
    }

    /**
     * 测试从云市场获取类别列表。
     * Test get category list from cloud market.
     *
     * @access public
     * @return string
     */
    public function getCategoriesTest(): string
    {
        $result     = $this->getCategories();
        $categories = '';
        foreach($result->categories as $categoryList) $categories .= $categoryList->alias . ' ';
        return $categories;
    }
}
