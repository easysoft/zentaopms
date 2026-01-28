#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getLogs();
timeout=0
cid=15750

- 执行compileTest模块的getLogsTest方法  @1
- 执行compileTest模块的getLogsTest方法  @1
- 执行compileTest模块的getLogsTest方法  @
- 执行compileTest模块的getLogsTest方法  @
- 执行compileTest模块的getLogsTest方法  @

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('compile');
$table->id->range('1-10');
$table->name->range('build_1,build_2,build_3,build_4,build_5,build_6,build_7,build_8,build_9,build_10');
$table->job->range('1-6');
$table->queue->range('100,200,0,300,null,400,500,600,700,800');
$table->status->range('success,failed,running,pending,created,success,failed,running,pending,created');
$table->logs->range('null');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->deleted->range('0');
$table->gen(10);

$jobTable = zenData('job');
$jobTable->id->range('1-6');
$jobTable->name->range('job_1,job_2,job_3,job_4,job_5,job_6');
$jobTable->repo->range('1-3');
$jobTable->engine->range('jenkins,gitlab,jenkins,gitlab,jenkins,gitlab');
$jobTable->pipeline->range('{"name": "test_pipeline"},{"project": 123, "reference": "master"},{"name": "jenkins_job"},{"project": 456},{"name": "build_job"},{"project": 789}');
$jobTable->server->range('1-3');
$jobTable->status->range('active');
$jobTable->lastSyncDate->range('`2024-01-01 10:00:00`');
$jobTable->deleted->range('0');
$jobTable->gen(6);

$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-6');
$pipelineTable->name->range('jenkins-server1,gitlab-server1,jenkins-server2,gitlab-server2,jenkins-server3,gitlab-server3');
$pipelineTable->type->range('jenkins,gitlab,jenkins,gitlab,jenkins,gitlab');
$pipelineTable->url->range('http://jenkins.test.com,http://gitlab.test.com,http://jenkins2.test.com,http://gitlab2.test.com,http://jenkins3.test.com,http://gitlab3.test.com');
$pipelineTable->account->range('admin,testuser,jenkins_user,gitlab_user,ci_user,deploy_user');
$pipelineTable->token->range('test_token_1,test_token_2,test_token_3,test_token_4,test_token_5,test_token_6');
$pipelineTable->password->range('test_pwd_1,test_pwd_2,test_pwd_3,test_pwd_4,test_pwd_5,test_pwd_6');
$pipelineTable->createdBy->range('admin');
$pipelineTable->createdDate->range('`2024-01-01 10:00:00`');
$pipelineTable->deleted->range('0');
$pipelineTable->gen(6);

zenData('repo')->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$compileTest = new compileModelTest();

// 5. 执行测试步骤（至少5个）
r(is_string($compileTest->getLogsTest((object)array('engine' => 'jenkins', 'server' => 1, 'pipeline' => '{"name": "test"}'), (object)array('id' => 1, 'queue' => 123)))) && p() && e('1');
r(is_string($compileTest->getLogsTest((object)array('engine' => 'gitlab', 'server' => 2, 'pipeline' => '{"project": 123}'), (object)array('id' => 2, 'queue' => 456)))) && p() && e('1');
r($compileTest->getLogsTest((object)array('engine' => 'jenkins', 'server' => 1, 'pipeline' => '{"name": "test"}'), (object)array('id' => 3, 'queue' => 0))) && p() && e('');
r($compileTest->getLogsTest((object)array('engine' => 'jenkins', 'server' => 1, 'pipeline' => ''), (object)array('id' => 4, 'queue' => 789))) && p() && e('');
r($compileTest->getLogsTest((object)array('engine' => 'unknown', 'server' => 1, 'pipeline' => '{"name": "test"}'), (object)array('id' => 5, 'queue' => 999))) && p() && e('');