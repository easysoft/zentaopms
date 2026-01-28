#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目1,迭代1,迭代2,阶段3,阶段4,看板5,看板6');
$execution->type->range('project,sprint{2},stage{2},kanban{2}');
$execution->parent->range('0,1,2,1,4,1,5');
$execution->status->range('wait');
$execution->gen(7);

su('admin');

/**

title=测试executionModel->getToImportTest();
timeout=0
cid=16346

*/

$sprintExecutions = array(2, 3);
$stageExecutions  = array(4, 5);
$kanbanExecutions = array(6, 7);
$type             = array('sprint', 'stage', 'kanban');
$count            = array(0, 1);

$execution = new executionModelTest();
r($execution->getToImportTest($sprintExecutions, $type[0], $count[0])) && p('2') && e('迭代1'); // 敏捷执行查询
r($execution->getToImportTest($stageExecutions,  $type[1], $count[0])) && p('4') && e('阶段3'); // 瀑布执行查询
r($execution->getToImportTest($kanbanExecutions, $type[2], $count[0])) && p('6') && e('看板5'); // 看板执行查询
r($execution->getToImportTest($sprintExecutions, $type[0], $count[1])) && p('')  && e('2');     // 敏捷执行统计
r($execution->getToImportTest($stageExecutions,  $type[1], $count[1])) && p('')  && e('2');     // 瀑布执行统计
r($execution->getToImportTest($kanbanExecutions, $type[2], $count[1])) && p('')  && e('2');     // 看板执行统计
