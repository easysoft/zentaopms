#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getPrevKanban();
timeout=0
cid=16335

- 测试步骤1：查询不存在看板配置的执行ID @empty
- 测试步骤2：保存看板数据后查询存在配置的执行ID第wait条的0属性 @task1
- 测试步骤3：查询执行ID为0的边界值情况 @empty
- 测试步骤4：查询负数执行ID的异常情况 @empty
- 测试步骤5：查询非常大的执行ID @empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('4');
$task->name->range('1-10')->prefix('任务');
$task->type->range('design,devel,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,closed');
$task->gen(10);

$executionTester = new executionModelTest();

$emptyExecutionID = 1;
$executionID      = 4;
$boundaryID       = 0;
$negativeID       = -1;
$largeID          = 99999;

r($executionTester->getPrevKanbanTest($emptyExecutionID)) && p() && e('empty');        // 测试步骤1：查询不存在看板配置的执行ID
$executionTester->saveKanbanDataTest($executionID);
r($executionTester->getPrevKanbanTest($executionID)) && p('wait:0') && e('task1');     // 测试步骤2：保存看板数据后查询存在配置的执行ID
r($executionTester->getPrevKanbanTest($boundaryID)) && p() && e('empty');              // 测试步骤3：查询执行ID为0的边界值情况
r($executionTester->getPrevKanbanTest($negativeID)) && p() && e('empty');              // 测试步骤4：查询负数执行ID的异常情况
r($executionTester->getPrevKanbanTest($largeID)) && p() && e('empty');                 // 测试步骤5：查询非常大的执行ID