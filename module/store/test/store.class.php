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
}
