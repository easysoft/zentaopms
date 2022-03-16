#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getToImportTest();
cid=1
pid=1

敏捷执行查询 >> 迭代1
瀑布执行查询 >> 阶段31
看板执行查询 >> 看板61
敏捷执行统计 >> 2
瀑布执行统计 >> 2
看板执行统计 >> 2

*/

$sprintExecutions = array('101', '102');
$stageExecutions  = array('131', '132');
$kanbanExecutions = array('161', '162');
$type            = array('sprint', 'stage', 'kanban');
$count           = array('0', '1');

$execution = new executionTest();
r($execution->getToImportTest($sprintExecutions,$type[0],$count[0])) && p('101') && e('迭代1');  // 敏捷执行查询
r($execution->getToImportTest($stageExecutions,$type[1],$count[0]))  && p('131') && e('阶段31'); // 瀑布执行查询
r($execution->getToImportTest($kanbanExecutions,$type[2],$count[0])) && p('161') && e('看板61'); // 看板执行查询
r($execution->getToImportTest($sprintExecutions,$type[0],$count[1])) && p('')    && e('2');      // 敏捷执行统计
r($execution->getToImportTest($stageExecutions,$type[1],$count[1]))  && p('')    && e('2');      // 瀑布执行统计
r($execution->getToImportTest($kanbanExecutions,$type[2],$count[1])) && p('')    && e('2');      // 看板执行统计