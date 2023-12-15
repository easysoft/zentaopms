#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试executionModel->setKanban();
timeout=0
cid=1

*/

$executionID         = 3;
$changeFixColWidth   = array('fluidBoard' => 0, 'colWidth' => '300');
$changeNoFixColWidth = array('fluidBoard' => 1, 'minColWidth' => '210', 'maxColWidth' => '400');
$changeCardCount     = array('displayCards' => 5);
$emptyFixColWidth    = array('fluidBoard' => 0, 'colWidth' => '0');
$emptyNoFixColWidth  = array('fluidBoard' => 1, 'minColWidth' => 0, 'maxColWidth' => 0);
$minGtMaxWidth       = array('fluidBoard' => 1, 'minColWidth' => '400', 'maxColWidth' => '210');

$executionTester = new executionTest();
$executionTester->executionModel->config->minColWidth = '200';
r($executionTester->setKanbanTest($executionID, $changeFixColWidth))   && p('fluidBoard,colWidth')                && e('0,300');                           // 测试修改固定列宽
r($executionTester->setKanbanTest($executionID, $changeNoFixColWidth)) && p('fluidBoard,minColWidth,maxColWidth') && e('1,210,400');                       // 测试修改自适应列宽
r($executionTester->setKanbanTest($executionID, $changeCardCount))     && p('displayCards')                       && e('5');                               // 测试修改卡片展示数量
r($executionTester->setKanbanTest($executionID, $emptyFixColWidth))    && p('colWidth:0')                         && e('『列宽』应当不小于『200』。');     // 测试修改固定列宽的必填判断
r($executionTester->setKanbanTest($executionID, $emptyNoFixColWidth))  && p('minColWidth:0')                      && e('『最小列宽』应当不小于『200』。'); // 测试修改自适应列宽的必填判断
r($executionTester->setKanbanTest($executionID, $minGtMaxWidth))       && p('maxColWidth:0')                      && e('『最大列宽』应当大于『400』。');   // 测试最小宽度大于最大宽度的检查
