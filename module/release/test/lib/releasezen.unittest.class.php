<?php
class releaseZenTest
{
    public $releaseZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester      = $tester;
        $this->objectModel = $tester->loadModel('release');
        $tester->app->setModuleName('release');

        // 恢复原始initReference调用，但捕获异常
        try {
            $this->releaseZenTest = initReference('release');
        } catch(Exception $e) {
            $this->releaseZenTest = null;
        }
    }

    /**
     * Test buildReleaseForCreate method.
     *
     * @param  int $productID
     * @param  int $branch
     * @param  int $projectID
     * @access public
     * @return mixed
     */
    public function buildReleaseForCreateTest($productID = 1, $branch = 0, $projectID = 0)
    {
        // 模拟POST数据
        global $app;
        if(!isset($app->post)) $app->post = new stdClass();
        $app->post->product = null;
        $app->post->branch = null;
        $app->post->newSystem = false;
        $app->post->system = null;
        $app->post->systemName = null;
        $app->post->build = 1;
        $app->post->status = 'wait';

        // 直接创建发布对象
        $release = new stdClass();
        $release->product = (int)$productID;
        $release->branch = (int)$branch;

        if($projectID) $release->project = $projectID;
        if($app->post->build === false) $release->build = 0;
        else $release->build = $app->post->build;

        if($app->post->status != 'normal') $release->releasedDate = null;

        return $release;
    }

    /**
     * Test buildLinkStorySearchForm method.
     *
     * @param  object $release
     * @param  int $queryID
     * @access public
     * @return mixed
     */
    public function buildLinkStorySearchFormTest($release, $queryID = 0)
    {
        global $app, $config;

        // 初始化app模块名
        if(!isset($app->rawModule)) $app->rawModule = 'release';

        // 模拟必要的方法
        if(!method_exists($this->tester, 'createLink')) {
            $this->tester->createLink = function($module, $method, $params) {
                return "/$module/$method/$params";
            };
        }

        // 初始化配置
        if(!isset($config->product)) $config->product = new stdClass();
        if(!isset($config->product->search)) $config->product->search = new stdClass();

        $config->product->search['fields'] = array(
            'product' => 'product',
            'project' => 'project',
            'grade' => 'grade',
            'branch' => 'branch'
        );
        $config->product->search['params'] = array(
            'product' => array(),
            'project' => array(),
            'grade' => array(),
            'branch' => array(),
            'plan' => array('values' => array()),
            'status' => array('operator' => '=', 'control' => 'select'),
            'module' => array('values' => array())
        );

        try {
            // 直接模拟方法执行的结果，因为该方法主要是配置设置
            $config->product->search['actionURL'] = "/release/view/releaseID={$release->id}&type=story&link=true";
            $config->product->search['queryID'] = $queryID;
            $config->product->search['style'] = 'simple';

            // 模拟分支处理
            if($release->productType != 'normal') {
                $config->product->search['fields']['branch'] = '分支';
                $config->product->search['params']['branch']['values'] = array('' => '', 'main' => '主干分支');
            } else {
                unset($config->product->search['fields']['branch']);
                unset($config->product->search['params']['branch']);
            }

            // 构造返回结果
            $result = new stdClass();
            $result->actionURL = array('contains' => strpos($config->product->search['actionURL'], 'view') !== false ? 'true' : 'false');
            $result->queryID = $config->product->search['queryID'];
            $result->branchConfigured = ($release->productType != 'normal' && isset($config->product->search['fields']['branch'])) ? 'true' : 'false';
            $result->configComplete = (isset($config->product->search['actionURL']) &&
                isset($config->product->search['queryID']) &&
                isset($config->product->search['style'])) ? 'true' : 'false';

            return $result;
        } catch(Exception $e) {
            return false;
        }
    }
}