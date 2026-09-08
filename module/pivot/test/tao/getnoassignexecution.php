#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

error_reporting(E_ERROR);

$zd_task = zenData('task');
$zd_task->id->range('1-5');
$zd_task->parent->range('0');
$zd_task->isParent->range('0');
$zd_task->isTpl->range('0');
$zd_task->design->range('0');
$zd_task->designVersion->range('0');
$zd_task->fromBug->range('0');
$zd_task->feedback->range('0');
$zd_task->fromIssue->range('0');
$zd_task->finishedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->canceledDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->activatedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->order->range('0');
$zd_task->repo->range('0');
$zd_task->mr->range('0');
$zd_task->deleted->range('0');
$zd_task->gen(5);
$zd_taskteam = zenData('taskteam');
$zd_taskteam->id->range('1-5');
$zd_taskteam->storyVersion->range('0');
$zd_taskteam->gen(5);
$zd_team = zenData('team');
$zd_team->id->range('1-5');
$zd_team->root->range('1-5');
$zd_team->type->range('1-5');
$zd_team->account->range('1-5');
$zd_team->teamgroup->range('0');
$zd_team->estimate->range('0');
$zd_team->consumed->range('0');
$zd_team->left->range('0');
$zd_team->order->range('0');
$zd_team->gen(5);
$zd_project = zenData('project');
$zd_project->id->range('1-5');
$zd_project->isTpl->range('0');
$zd_project->charter->range('0');
$zd_project->milestone->range('0');
$zd_project->workflowGroup->range('0');
$zd_project->firstEnd->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD');
$zd_project->realBegan->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD');
$zd_project->realEnd->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD');
$zd_project->days->range('0');
$zd_project->pri->range('0');
$zd_project->version->range('0');
$zd_project->parentVersion->range('0');
$zd_project->planDuration->range('0');
$zd_project->realDuration->range('0');
$zd_project->estimate->range('0');
$zd_project->left->range('0');
$zd_project->consumed->range('0');
$zd_project->teamCount->range('0');
$zd_project->market->range('0');
$zd_project->PI->range('0');
$zd_project->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->canceledDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->suspendedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->displayCards->range('0');
$zd_project->fluidBoard->range('0');
$zd_project->parallel->range('0');
$zd_project->colWidth->range('0');
$zd_project->minColWidth->range('0');
$zd_project->maxColWidth->range('0');
$zd_project->coverExecutionPriv->range('0');
$zd_project->deleted->range('0');
$zd_project->gen(5);
$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('pivot');
$tester->loadTao('pivot');


$testObj = new pivotTaoTest();
/**

title=测试 pivotModel::getNoAssignExecution()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

r($testObj->getNoAssignExecutionTest(array())) && p() && e('0'); // 步骤1：正常输入
r($testObj->getNoAssignExecutionTest(array())) && p() && e('0'); // 步骤2：边界值输入
r($testObj->getNoAssignExecutionTest(array(-1))) && p() && e('0'); // 步骤3：无效输入
r($testObj->getNoAssignExecutionTest(array(999999))) && p() && e('0'); // 步骤4：大值输入
r($testObj->getNoAssignExecutionTest(array(1, 2))) && p() && e('0'); // 步骤5：业务规则验证
