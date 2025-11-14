#!/usr/bin/env php
<?php

/**

title=测试 jobModel::checkParameterizedBuild();
timeout=0
cid=16836

- 步骤1：正常Jenkins Job（无参数化构建） @0
- 步骤2：参数化Job检查（模拟返回） @0
- 步骤3：不存在的Job ID @0
- 步骤4：无效服务器配置（空URL） @0
- 步骤5：边界值测试（ID为0） @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备
$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-5');
$pipelineTable->type->range('jenkins{5}');
$pipelineTable->name->range('Jenkins服务器{5}');
$pipelineTable->url->range('[https://jenkins1.test.com],[https://jenkins2.test.com],[],[https://jenkins3.test.com],[https://jenkins4.test.com]');
$pipelineTable->account->range('[jenkins],[admin],[],[testuser],[devops]');
$pipelineTable->token->range('[token1],[token2],[],[token3],[token4]');
$pipelineTable->gen(5);

$jobTable = zenData('job');
$jobTable->id->range('1-5');
$jobTable->name->range('正常Job,参数Job,空Job,测试Job,开发Job');
$jobTable->engine->range('jenkins{5}');
$jobTable->server->range('1,2,3,4,5');
$jobTable->pipeline->range('[/job/simple-job/],[/job/parameterized-job/],[],[/job/test-job/],[/job/dev-job/]');
$jobTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$jobTest = new jobTest();

// 5. 执行测试步骤（至少5个）
r($jobTest->checkParameterizedBuildTest(1)) && p() && e('0'); // 步骤1：正常Jenkins Job（无参数化构建）
r($jobTest->checkParameterizedBuildTest(2)) && p() && e('0'); // 步骤2：参数化Job检查（模拟返回）
r($jobTest->checkParameterizedBuildTest(999)) && p() && e('0'); // 步骤3：不存在的Job ID
r($jobTest->checkParameterizedBuildTest(3)) && p() && e('0'); // 步骤4：无效服务器配置（空URL）
r($jobTest->checkParameterizedBuildTest(0)) && p() && e('0'); // 步骤5：边界值测试（ID为0）