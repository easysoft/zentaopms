#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zdTable('task');
$task->id->range('1-10');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

$bug = zdTable('bug');
$bug->id->range('1-10');
$bug->title->range('1-10')->prefix('Bug');
$bug->project->range('1');
$bug->product->range('1');
$bug->execution->range('3-5');
$bug->status->range('active,wait,doing');
$bug->gen(10);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('正常产品1,多分支产品1,多平台产品1');
$product->type->range('normal,branch,platform');
$product->status->range('closed{2},normal');
$product->createdBy->range('admin,user1');
$product->gen(3);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('5-10');
$projectproduct->product->range('1-3');
$projectproduct->plan->range('1-3');
$projectproduct->gen(5);

su('admin');

/**

title=测试executionModel->getSearchBugs();
cid=1
pid=1

查询产品1bug名称 >> Bug10
查询执行3bug名称 >> Bug1

*/

$productIDList   = array(1 => '正常产品1');
$executionIDList = array('3');
$sql             = '1=1';

$execution = new executionTest();
r($execution->getSearchBugsTest($productIDList, 0, $sql))            && p('10:title') && e('Bug10'); // 查询产品1bug名称
r($execution->getSearchBugsTest(array(), $executionIDList[0], $sql)) && p('1:title')  && e('Bug1');  // 查询执行3bug名称
