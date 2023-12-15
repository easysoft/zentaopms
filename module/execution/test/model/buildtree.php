#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

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
$task->module->range('1-10');
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
$product->root->range('3-5');
$product->parent->range('0,1{9}');
$product->type->range('task');
$product->gen(10);

$branch = zdTable('branch');
$branch->id->range('1-10');
$branch->product->range('1-3');
$branch->gen(5);

$related = zdTable('projectproduct');
$related->project->range('3-5');
$related->product->range('1-3');
$related->branch->range('0-1');
$related->gen(5);

/**

title=测试executionModel->buildTree();
timeout=0
cid=1

*/

$execution = new executionTest();
$executionIDList = array(0, 3);

r($execution->buildTreeTest($executionIDList[0])) && p() && e('0'); // 查询不存在的执行

$tree = $execution->buildTreeTest($executionIDList[1]);
r(count($tree[0]['items'])) && p()               && e('1');                                               // 查询存在的执行
r($tree[0])                 && p('content:html') && e("<span class=' title' title='模块1'>模块1</span>"); // 查询存在的执行
