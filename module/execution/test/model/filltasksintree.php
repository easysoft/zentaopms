#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
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

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('1-10')->prefix('模块');
$module->root->range('1-3');
$module->parent->range('0,1{9}');
$module->type->range('task');
$module->gen(10);

zenData('team')->gen(0);
zenData('story')->gen(0);
zenData('projectstory')->gen(0);
zenData('projectproduct')->loadYaml('projectprocuct')->gen(10);
zenData('user')->gen(5);
su('admin');

/**

title=测试executionModel->fillTasksInTree();
timeout=0
cid=16294

 - 敏捷执行查询属性name @0
- 瀑布执行查询属性name @0
- 看板执行查询属性name @0
- 错误执行查询 @0
- 项目查询属性name @模块1

*/

$executionIDList = array('0', '3', '4', '5', '1');

$execution = new executionModelTest();
r($execution->fillTasksInTreeTest($executionIDList[1])) && p('name') && e('0');     // 敏捷执行查询
r($execution->fillTasksInTreeTest($executionIDList[2])) && p('name') && e('0');     // 瀑布执行查询
r($execution->fillTasksInTreeTest($executionIDList[3])) && p('name') && e('0');     // 看板执行查询
r($execution->fillTasksInTreeTest($executionIDList[0])) && p()       && e('0');     // 错误执行查询
r($execution->fillTasksInTreeTest($executionIDList[4])) && p('name') && e('模块1'); // 项目查询
