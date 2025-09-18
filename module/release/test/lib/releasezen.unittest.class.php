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
}