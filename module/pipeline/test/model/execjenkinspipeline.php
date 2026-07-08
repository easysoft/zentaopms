#!/usr/bin/env php
<?php

/**

title=测试 jobModel::execJenkinsPipeline();
timeout=0
cid=16840

- 步骤1：基础pipeline对象属性ZENTAO_DATA @compile=1
- 步骤2：包含tag参数属性PARAM_TAG @v1.0.0
- 步骤3：没有tag时PARAM_TAG为空属性PARAM_TAG @~~
- 步骤4：额外参数属性EXTRA_PARAM @extra_value
- 步骤5：不同编译ID属性ZENTAO_DATA @compile=5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 3) . '/model.php';

su('admin');

class execJenkinsPipelineCompileMock
{
    public function getBuildUrl(object $job): object
    {
        $url = new stdclass();
        $url->url     = 'http://jenkins.test.com/buildWithParameters/api/json';
        $url->userPWD = 'testuser:test_token';
        return $url;
    }
}

class execJenkinsPipelineCIMock
{
    public ?object $lastData = null;

    public function sendRequest(string $url, object $data, string $userPWD = ''): string|int
    {
        $this->lastData = clone $data;
        return 1001;
    }
}

class execJenkinsPipelineModelMock extends jobModel
{
    public execJenkinsPipelineCompileMock $compileMock;
    public execJenkinsPipelineCIMock $ciMock;

    public function __construct()
    {
        parent::__construct();
        $this->compileMock = new execJenkinsPipelineCompileMock();
        $this->ciMock      = new execJenkinsPipelineCIMock();
    }

    public function loadModel($moduleName, $appName = ''): object|bool
    {
        if($moduleName == 'compile') return $this->compileMock;
        if($moduleName == 'ci') return $this->ciMock;
        return parent::loadModel($moduleName, $appName);
    }
}

class execJenkinsPipelineRunner
{
    public execJenkinsPipelineModelMock $model;

    public function __construct()
    {
        $this->model = new execJenkinsPipelineModelMock();
    }

    public function run(object $job, object $repo, int $compileID, array $extraParam = array()): object
    {
        return $this->model->execJenkinsPipeline($job, $repo, $compileID, $extraParam);
    }

    public function getPipeline(): ?object
    {
        return $this->model->ciMock->lastData;
    }
}

$commitJob = (object)array(
    'id'          => 1,
    'triggerType' => 'commit',
    'lastTag'     => '',
    'customParam' => '[]',
    'product'     => 1
);

$tagJob = (object)array(
    'id'          => 2,
    'triggerType' => 'tag',
    'lastTag'     => 'v1.0.0',
    'customParam' => '[]',
    'product'     => 1
);

$repo   = (object)array('id' => 1, 'path' => '/test/repo/path');
$runner = new execJenkinsPipelineRunner();

$runner->run($commitJob, $repo, 1);
r($runner->getPipeline()) && p('ZENTAO_DATA') && e('compile=1'); // 步骤1：基础pipeline对象

$runner->run($tagJob, $repo, 2);
r($runner->getPipeline()) && p('PARAM_TAG') && e('v1.0.0'); // 步骤2：包含tag参数

$runner->run($commitJob, $repo, 1);
r($runner->getPipeline()) && p('PARAM_TAG') && e('~~'); // 步骤3：没有tag时PARAM_TAG为空

$runner->run($commitJob, $repo, 4, array('EXTRA_PARAM' => 'extra_value'));
r($runner->getPipeline()) && p('EXTRA_PARAM') && e('extra_value'); // 步骤4：额外参数

$runner->run($commitJob, $repo, 5);
r($runner->getPipeline()) && p('ZENTAO_DATA') && e('compile=5'); // 步骤5：不同编译ID
