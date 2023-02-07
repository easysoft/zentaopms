#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->openedVersion->range('18.0');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zdTable('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$product = zdTable('module');
$product->id->range('1-10');
$product->name->range('1-10')->prefix('模块');
$product->root->range('1-3');
$product->parent->range('0,1{9}');
$product->type->range('task');
$product->gen(10);

zdTable('team')->gen(0);
su('admin');

/**

title=测试executionModel->fillTasksInTree();
cid=1
pid=1

敏捷执行查询 >> 产品1
瀑布执行查询 >> 产品2
看板执行查询 >> 产品1
错误执行查询 >> 0

*/

$executionIDList = array('0', '3', '4', '5');

$execution = new executionTest();
r($execution->fillTasksInTreeTest($executionIDList[1])) && p('name') && e('产品1'); // 敏捷执行查询
r($execution->fillTasksInTreeTest($executionIDList[2])) && p('name') && e('产品2'); // 瀑布执行查询
r($execution->fillTasksInTreeTest($executionIDList[3])) && p('name') && e('产品1'); // 看板执行查询
r($execution->fillTasksInTreeTest($executionIDList[0])) && p()       && e('0');     // 错误执行查询
