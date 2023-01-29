#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,看板1,看板2,看板3');
$execution->type->range('program,project,,kanban{3}');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$kanbanCell = zdTable('kanbancell');
$kanbanCell->id->range('1-9');
$kanbanCell->kanban->range('3{3},4{3},5{3}');
$kanbanCell->lane->range('1-9');
$kanbanCell->column->range('1-9');
$kanbanCell->type->range('task,bug,story');
$kanbanCell->cards->range('`,1,2,3,`{3},`,4,5,`{3},6{3}');
$kanbanCell->gen(9);

$kanbanColumn = zdTable('kanbancolumn');
$kanbanColumn->id->range('1-9');
$kanbanColumn->name->range('1-9')->prefix('看板列');
$kanbanColumn->type->range('1-9')->prefix('column');
$kanbanColumn->gen(9);

$CFD = zdTable('cfd');
$CFD->id->range('1-5');
$CFD->execution->range('3');
$CFD->count->range('1');
$CFD->type->range('task,bug,story');
$CFD->date->range('20220122 000000:0')->type('timestamp')->format('YY/MM/DD');
$CFD->name->range('1-5')->prefix('看板列');
$CFD->gen(5);

/**

title=测试executionModel->checkCFDData();
cid=1
pid=1

已有日期的情况 >> 5
未来日期的情况 >> 0

*/

$executionTester = new executionTest();
$dateList        = array('2022-01-22', '2099-01-01');

r(count($executionTester->checkCFDDataTest(3, $dateList[0]))) && p() && e('5'); // 已有日期的情况
r(count($executionTester->checkCFDDataTest(3, $dateList[1]))) && p() && e('0'); // 未来日期的情况
