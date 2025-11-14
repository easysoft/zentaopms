#!/usr/bin/env php
<?php

/**

title=测试 jobModel::initJob();
timeout=0
cid=16852

- 步骤1：正常情况-schedule类型当天触发 @1
- 步骤2：正常情况-commit类型触发器 @1
- 步骤3：边界值测试-空ID传入 @0
- 步骤4：边界值测试-空triggerType @0
- 步骤5：异常情况测试-无效triggerType但ID有效 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('job');
$table->id->range('1-10');
$table->name->range('Job{1-10}');
$table->repo->range('1-5');
$table->product->range('1-3');
$table->engine->range('jenkins{3},gitlab{3},svn{4}');
$table->triggerType->range('schedule{3},tag{3},commit{4}');
$table->atDay->range('0,1,2,3,4,5,6');
$table->atTime->range('09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00,18:00');
$table->svnDir->range('tags,branches,trunk');
$table->gen(10);

$repoTable = zenData('repo');
$repoTable->id->range('1-5');
$repoTable->name->range('Repo{1-5}');
$repoTable->SCM->range('Git{3},Subversion{2}');
$repoTable->path->range('/var/repo1,/var/repo2,/var/repo3,/var/repo4,/var/repo5');
$repoTable->gen(5);

$compileTable = zenData('compile');
$compileTable->id->range('1-5');
$compileTable->job->range('1-5');
$compileTable->status->range('success,running,failed,waiting,created');
$compileTable->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$compileTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($jobTest->initJobTest(1, (object)array('triggerType' => 'schedule', 'atDay' => date('w'), 'atTime' => '10:00'))) && p() && e('1'); // 步骤1：正常情况-schedule类型当天触发
r($jobTest->initJobTest(2, (object)array('triggerType' => 'commit', 'repo' => 1))) && p() && e('1'); // 步骤2：正常情况-commit类型触发器
r($jobTest->initJobTest(0, (object)array('triggerType' => 'schedule'))) && p() && e('0'); // 步骤3：边界值测试-空ID传入
r($jobTest->initJobTest(1, (object)array('triggerType' => ''))) && p() && e('0'); // 步骤4：边界值测试-空triggerType
r($jobTest->initJobTest(1, (object)array('triggerType' => 'invalid'))) && p() && e('1'); // 步骤5：异常情况测试-无效triggerType但ID有效