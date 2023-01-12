#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
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

$burn = zdTable('burn');
$burn->execution->range('3{5},4{5},5{5}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('94.3,56.3,55.3,37.8,33.8');
$burn->left->range('95.3,68.5,73.9,40.2,36,3');
$burn->consumed->range('20.1,33.4,41,56.55,59.55');
$burn->storyPoint->range('0,16.5,16,11.5,9');
$burn->gen(15);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$task = zdTable('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('4');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

zdTable('projectproduct')->gen(0);

/**

title=测试executionModel->formatTasksForTree();
cid=1
pid=1

查询不存在的执行 >> 0
查询存在的执行   >> 任务10

*/

$executionIDList = array(0, 4);

$execution = new executionTest();
r($execution->formatTasksForTreeTest($executionIDList[0])) && p()          && e('0');      // 查询不存在的执行
r($execution->formatTasksForTreeTest($executionIDList[1])) && p('0:title') && e('任务10'); // 查询存在的执行

