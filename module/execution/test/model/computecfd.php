#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试executionModel->computeCFD();
timeout=0
cid=16287

- 获取所有看板执行的累计卡片个数 @6
- 获取看板1的累计流图信息
 - 第7条的execution属性 @3
 - 第7条的type属性 @task
- 获取看板1的累计流图信息
 - 第8条的execution属性 @3
 - 第8条的type属性 @bug
- 获取看板1的累计流图信息
 - 第9条的execution属性 @3
 - 第9条的type属性 @story

*/

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,看板1,看板2,看板3');
$execution->type->range('program,project,,kanban{3}');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$kanbanCell = zenData('kanbancell');
$kanbanCell->id->range('1-9');
$kanbanCell->kanban->range('3{3},4{3},5{3}');
$kanbanCell->lane->range('1-9');
$kanbanCell->column->range('1-9');
$kanbanCell->type->range('task,bug,story');
$kanbanCell->cards->range('`,1,2,3,`{3},`,4,5,`{3},6{3}');
$kanbanCell->gen(9);

$kanbanColumn = zenData('kanbancolumn');
$kanbanColumn->id->range('1-9');
$kanbanColumn->name->range('1-9')->prefix('看板列');
$kanbanColumn->type->range('1-9')->prefix('column');
$kanbanColumn->gen(9);

$CFD = zenData('cfd');
$CFD->id->range('1');
$CFD->gen(0);

$executionTester = new executionModelTest();
$allExecutionCFDList    = $executionTester->computeCFDTest();
$singleExecutionCFDList = $executionTester->computeCFDTest(3);

r(count($allExecutionCFDList)) && p()                   && e('6');       // 获取所有看板执行的累计卡片个数
r($singleExecutionCFDList)     && p('7:execution,type') && e('3,task');  // 获取看板1的累计流图信息
r($singleExecutionCFDList)     && p('8:execution,type') && e('3,bug');   // 获取看板1的累计流图信息
r($singleExecutionCFDList)     && p('9:execution,type') && e('3,story'); // 获取看板1的累计流图信息
