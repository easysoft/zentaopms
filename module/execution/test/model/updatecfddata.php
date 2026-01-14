#!/usr/bin/env php
<?php

/**

title=测试 executionModel::updateCFDData();
timeout=0
cid=16376

- 测试步骤1：已有日期的情况 @5
- 测试步骤2：未来日期的情况 @0
- 测试步骤3：不存在数据的历史日期情况 @5
- 测试步骤4：无效execution ID @0
- 测试步骤5：测试今日边界情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,看板1,看板2,看板3');
$execution->type->range('program,project,kanban{3}');
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
$CFD->count->range('1-5');
$CFD->type->range('task,bug,story,requirement,story');
$CFD->date->range('20220120 000000:0,20220121 000000:0,20220122 000000:0{3}')->type('timestamp')->format('YY/MM/DD');
$CFD->name->range('backlog,doing,testing,done,closed');
$CFD->gen(5);

$executionTester = new executionModelTest();

r(count($executionTester->updateCFDDataTest(3, '2022-01-22'))) && p() && e('5');  // 测试步骤1：已有日期的情况
r(count($executionTester->updateCFDDataTest(3, '2099-01-01'))) && p() && e('0');  // 测试步骤2：未来日期的情况
r(count($executionTester->updateCFDDataTest(3, '2022-01-23'))) && p() && e('5');  // 测试步骤3：不存在数据的历史日期情况
r(count($executionTester->updateCFDDataTest(999, '2022-01-20'))) && p() && e('0'); // 测试步骤4：无效execution ID
r(count($executionTester->updateCFDDataTest(3, helper::today()))) && p() && e('0'); // 测试步骤5：测试今日边界情况