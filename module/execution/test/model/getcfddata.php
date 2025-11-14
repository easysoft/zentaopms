#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试executionModel->getCFDData();
timeout=0
cid=16307

- 不存在执行的累计流图信息 @0
- 存在的执行的需求卡片累计流图信息第2022-01-22条的name属性 @看板列3
- 存在的执行的任务卡片累计流图信息第2022-01-22条的name属性 @看板列1
- 存在的执行的Bug卡片累计流图信息第2022-01-22条的name属性 @看板列2
- 存在的执行的测试卡片累计流图信息 @0

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
$CFD->id->range('1-5');
$CFD->execution->range('3');
$CFD->count->range('1');
$CFD->type->range('task,bug,story');
$CFD->date->range('20220122 000000:0')->type('timestamp')->format('YY/MM/DD');
$CFD->name->range('1-5')->prefix('看板列');
$CFD->gen(5);

$typeList = array('story', 'task', 'bug', 'test');

$executionTester = new executionTest();
r(count($executionTester->getCFDDataTest()))                  && p()                  && e('0');       // 不存在执行的累计流图信息
r(current($executionTester->getCFDDataTest(3, $typeList[0]))) && p('2022-01-22:name') && e('看板列3'); // 存在的执行的需求卡片累计流图信息
r(current($executionTester->getCFDDataTest(3, $typeList[1]))) && p('2022-01-22:name') && e('看板列1'); // 存在的执行的任务卡片累计流图信息
r(current($executionTester->getCFDDataTest(3, $typeList[2]))) && p('2022-01-22:name') && e('看板列2'); // 存在的执行的Bug卡片累计流图信息
r(current($executionTester->getCFDDataTest(3, $typeList[3]))) && p()                  && e('0');       // 存在的执行的测试卡片累计流图信息
