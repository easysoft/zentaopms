#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目集1,项目1,项目2,迭代1,阶段1,看板1');
$execution->type->range('program,project{2},sprint,stage,kanban');
$execution->code->range('1-6')->prefix('code');
$execution->parent->range('0,1{2},2{2},3');
$execution->project->range('0,1{2},2{2},3');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->hasProduct->range('0');
$execution->begin->range('20220110 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220220 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

$burn = zenData('burn');
$burn->execution->range('3{5},4{5},5{5}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('94.3,56.3,55.3,37.8,33.8');
$burn->left->range('95.3,68.5,73.9,40.2,36,3');
$burn->consumed->range('20.1,33.4,41,56.55,59.55');
$burn->storyPoint->range('0,16.5,16,11.5,9');
$burn->gen(15);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('4');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

zenData('projectproduct')->gen(0);

/**

title=测试 executionTao::formatTasksForTree();
cid=0

- 测试步骤1：空执行ID输入 >> 返回空数组
- 测试步骤2：不存在的执行ID >> 返回空数组
- 测试步骤3：存在任务的执行ID >> 验证返回任务数量
- 测试步骤4：验证任务基本属性 >> 验证title和type属性
- 测试步骤5：验证任务状态属性 >> 验证status和id属性
- 测试步骤6：验证任务工时属性 >> 验证estimate和consumed属性
- 测试步骤7：验证任务优先级属性 >> 验证pri和left属性

*/

$executionIDList = array(0, 999, 4, 4, 4, 4, 4);

$execution = new executionTest();
r($execution->formatTasksForTreeTest($executionIDList[0])) && p() && e('0'); // 测试步骤1：空执行ID输入
r($execution->formatTasksForTreeTest($executionIDList[1])) && p() && e('0'); // 测试步骤2：不存在的执行ID
r($execution->formatTasksForTreeTest($executionIDList[2])) && p() && e('10'); // 测试步骤3：存在任务的执行ID
r($execution->formatTasksForTreeTest($executionIDList[3])) && p('0:title,0:type') && e('任务10,task'); // 测试步骤4：验证任务基本属性
r($execution->formatTasksForTreeTest($executionIDList[4])) && p('0:status,0:id') && e('wait,10'); // 测试步骤5：验证任务状态属性
r($execution->formatTasksForTreeTest($executionIDList[5])) && p('0:estimate,0:consumed') && e('10,10'); // 测试步骤6：验证任务工时属性
r($execution->formatTasksForTreeTest($executionIDList[6])) && p('0:pri,0:left') && e('3,10'); // 测试步骤7：验证任务优先级属性