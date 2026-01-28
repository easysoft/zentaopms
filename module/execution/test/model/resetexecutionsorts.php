#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-8');
$execution->name->range('项目集1,项目1,需求阶段1,测试阶段1,需求子阶段1,需求子阶段2,需求子阶段1的迭代1,需求子阶段1的看板1');
$execution->model->range('[],waterfallplus,[]{6}');
$execution->type->range('program,project,stage{4},sprint,kanban');
$execution->project->range('0{2},2{6}');
$execution->parent->range('0,1,2{2},3{2},5{2}');
$execution->grade->range('1{4},2{2},3{2}');
$execution->path->range('`,1,`,`,1,2,`,`,1,2,3`,`,1,2,4,`,`,1,2,3,5,`,`,1,2,3,6,`,`,1,2,3,5,7,`,`1,2,3,5,8,`');
$execution->order->range('5,10,15,20,25,30,35,40');
$execution->status->range('doing');
$execution->openedBy->range('admin');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(8);

/**

title=测试executionModel->resetExecutionSorts();
timeout=0
cid=16361

- 检查没有执行时的排序 @0
- 检查项目 2 的执行排序 @3,5,7,8,6,4

- 检查项目 60 的执行排序 @106,112,107,113,108,114,109,115,110,116,111,117

- 检查项目 61 的执行排序 @118,119,120,121,122,123

- 检查项目 100 的执行排序 @124,125,126

*/

$projectList = array(0, 2, 60, 61, 100);

$executionTester = new executionModelTest();
r($executionTester->resetExecutionSortsTest($projectList[0])) && p() && e('0');           // 检查没有执行时的排序
r($executionTester->resetExecutionSortsTest($projectList[1])) && p() && e('3,5,7,8,6,4'); // 检查项目 2 的执行排序

zenData('project')->loadYaml('execution')->gen('30');

r($executionTester->resetExecutionSortsTest($projectList[2])) && p() && e('106,112,107,113,108,114,109,115,110,116,111,117'); // 检查项目 60 的执行排序
r($executionTester->resetExecutionSortsTest($projectList[3])) && p() && e('118,119,120,121,122,123');                         // 检查项目 61 的执行排序
r($executionTester->resetExecutionSortsTest($projectList[4])) && p() && e('124,125,126');                                     // 检查项目 100 的执行排序
