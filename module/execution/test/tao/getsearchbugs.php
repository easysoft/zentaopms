#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$execution = zenData('project');
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

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->title->range('1-10')->prefix('Bug');
$bug->project->range('1');
$bug->product->range('1');
$bug->execution->range('3-5');
$bug->module->range('1');
$bug->status->range('active');
$bug->assignedTo->range('admin');
$bug->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('正常产品1,多分支产品1,多平台产品1');
$product->type->range('normal,branch,platform');
$product->status->range('closed{2},normal');
$product->createdBy->range('admin,user1');
$product->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('5-10');
$projectproduct->product->range('1-3');
$projectproduct->plan->range('1-3');
$projectproduct->gen(5);

su('admin');

/**

title=测试executionModel->getSearchBugs();
timeout=0
cid=16392

- 查询产品1bug名称第10条的title属性 @Bug10
- 查询执行3bug名称第1条的title属性 @Bug1
- 查询模块1的Bug第10条的module属性 @1
- 查询执行3的Bug第10条的execution属性 @3
- 查询名称中有3的Bug第3条的title属性 @Bug3
- 查询指派给admin的Bug第4条的assignedTo属性 @admin

*/

$productIDList   = array(1);
$executionIDList = array(0, 3);

$sqlList[0] = '1=1';
$sqlList[1] = '`module` = 1';
$sqlList[2] = '`execution` = 3';
$sqlList[3] = "`title` LIKE '%3%'";
$sqlList[4] = "`assignedTo` = 'admin'";

global $tester;
$tester->loadModel('execution');
r($tester->execution->getSearchBugs($productIDList, $executionIDList[0], $sqlList[0])) && p('10:title')     && e('Bug10'); // 查询产品1bug名称
r($tester->execution->getSearchBugs(array(),        $executionIDList[1], $sqlList[0])) && p('1:title')      && e('Bug1');  // 查询执行3bug名称
r($tester->execution->getSearchBugs($productIDList, $executionIDList[0], $sqlList[1])) && p('10:module')    && e('1');     // 查询模块1的Bug
r($tester->execution->getSearchBugs($productIDList, $executionIDList[0], $sqlList[2])) && p('10:execution') && e('3');     // 查询执行3的Bug
r($tester->execution->getSearchBugs($productIDList, $executionIDList[0], $sqlList[3])) && p('3:title')      && e('Bug3');  // 查询名称中有3的Bug
r($tester->execution->getSearchBugs($productIDList, $executionIDList[0], $sqlList[4])) && p('4:assignedTo') && e('admin'); // 查询指派给admin的Bug