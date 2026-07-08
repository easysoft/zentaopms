#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getExecutionList();
timeout=0
cid=0

- 查询所有执行记录，期望返回3条 @3
- 按pipelineID=1过滤，期望返回2条 @2
- 按spaceID=1过滤，期望pipelineName属性 @流水线A
- 按repoID=1过滤，期望返回1条 @1
- 按type=space过滤，期望返回2条 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $app;
$app->rawModule = 'pipeline';
$app->rawMethod = 'getexecutionlist';

/* 使用 zendata 准备测试数据 */
$pipeline = zenData('ops_pipeline');
$pipeline->id->range('1-2');
$pipeline->name->range('流水线A,流水线B');
$pipeline->engine->range('gitfox,jenkins');
$pipeline->scope->range('space,repo');
$pipeline->spaceID->range('1{2}');
$pipeline->repoID->range('0,1');
$pipeline->status->range('active{2}');
$pipeline->defaultBranch->range('master{2}');
$pipeline->createdBy->range('admin{2}');
$pipeline->deleted->range('0');
$pipeline->gen(2);

$execution = zenData('ops_pipeline_executions');
$execution->id->range('1-3');
$execution->pipelineID->range('1,1,2');
$execution->status->range('success,failure,success');
$execution->createdBy->range('admin,admin,user');
$execution->createdDate->range('[2024-06-01 10:00:00],[2024-06-02 10:00:00],[2024-06-03 10:00:00]');
$execution->trigger->range('commit,tag,manual');
$execution->gen(3);

$tester = new pipelineModelTest();

$allList = $tester->getExecutionListTest();
r(count($allList)) && p() && e(3); // 步骤1：查询所有执行记录，共3条

$pipeList = $tester->getExecutionListTest(0, 0, '', 1, 'id_asc', 20, 1);
r(count($pipeList)) && p() && e(2); // 步骤2：按pipelineID=1过滤，共2条

$spaceList = $tester->getExecutionListTest(1, 0, '', 0, 'id_asc', 20, 1);
r(current($spaceList)) && p('pipelineName') && e('流水线A'); // 步骤3：按spaceID=1过滤，首条pipelineName为流水线A

$repoList = $tester->getExecutionListTest(0, 1, '', 0, 'id_asc', 20, 1);
r(count($repoList)) && p() && e(1); // 步骤4：按repoID=1过滤，共1条

$typeSpaceList = $tester->getExecutionListTest(0, 0, 'space', 0, 'id_asc', 20, 1);
r(count($typeSpaceList)) && p() && e(2); // 步骤5：按type=space过滤，共2条
