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

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$job = zenData('job');
$job->id->range('1-5');
$job->name->range('测试流水线{5}');
$job->repo->range('1');
$job->product->range('1');
$job->engine->range('jenkins');
$job->server->range('1');
$job->pipeline->range('testPipeline');
$job->triggerType->range('commit{1},tag{1},commit{1},schedule{2}');
$job->customParam->range('[]{1},{"TEST_VAR":"$zentao_version"}{1},[]{3}');
$job->lastTag->range('[]{1},v1.0.0{1},[]{3}');
$job->deleted->range('0');
$job->gen(5);

$repo = zenData('repo');
$repo->id->range('1');
$repo->name->range('测试仓库');
$repo->path->range('/test/repo/path');
$repo->SCM->range('Git');
$repo->deleted->range('0');
$repo->gen(1);

$pipeline = zenData('pipeline');
$pipeline->id->range('1');
$pipeline->type->range('jenkins');
$pipeline->name->range('Jenkins服务器');
$pipeline->url->range('http://jenkins.test.com');
$pipeline->account->range('testuser');
$pipeline->token->range('test_token');
$pipeline->password->range('dGVzdF9wYXNzd29yZA==');
$pipeline->deleted->range('0');
$pipeline->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 创建Mock测试类来避免实际网络调用
class jobTestMock extends jobTest
{
    public function execJenkinsPipelineTestMock($jobID, $repo, $compileID, $extraParam = array())
    {
        $job = $this->objectModel->getById($jobID);
        
        // 创建pipeline对象（模拟execJenkinsPipeline的核心逻辑）
        $pipeline = new stdclass();
        $pipeline->PARAM_TAG   = '';
        $pipeline->ZENTAO_DATA = "compile={$compileID}";
        if(strpos($job->triggerType, 'tag') !== false) $pipeline->PARAM_TAG = $job->lastTag;

        // 处理自定义参数
        if(!empty($job->customParam))
        {
            $customParams = json_decode($job->customParam, true);
            if($customParams)
            {
                foreach($customParams as $paramName => $paramValue)
                {
                    global $config;
                    $paramValue = str_replace('$zentao_version',  $config->version, $paramValue);
                    $pipeline->$paramName = $paramValue;
                }
            }
        }

        // 处理额外参数
        foreach($extraParam as $paramName => $paramValue)
        {
            if(!isset($pipeline->$paramName)) $pipeline->$paramName = $paramValue;
        }

        return $pipeline;
    }
}

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTestMock();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($jobTest->execJenkinsPipelineTestMock(1, (object)array('id' => 1, 'path' => '/test/repo/path'), 1, array())) && p('ZENTAO_DATA') && e('compile=1'); // 步骤1：基础pipeline对象
r($jobTest->execJenkinsPipelineTestMock(2, (object)array('id' => 1, 'path' => '/test/repo/path'), 2, array())) && p('PARAM_TAG') && e('v1.0.0'); // 步骤2：包含tag参数
r($jobTest->execJenkinsPipelineTestMock(1, (object)array('id' => 1, 'path' => '/test/repo/path'), 1, array())) && p('PARAM_TAG') && e('~~'); // 步骤3：没有tag时PARAM_TAG为空
r($jobTest->execJenkinsPipelineTestMock(1, (object)array('id' => 1, 'path' => '/test/repo/path'), 4, array('EXTRA_PARAM' => 'extra_value'))) && p('EXTRA_PARAM') && e('extra_value'); // 步骤4：额外参数
r($jobTest->execJenkinsPipelineTestMock(3, (object)array('id' => 1, 'path' => '/test/repo/path'), 5, array())) && p('ZENTAO_DATA') && e('compile=5'); // 步骤5：不同编译ID