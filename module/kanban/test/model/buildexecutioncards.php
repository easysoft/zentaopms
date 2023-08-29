#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('story')->gen(10);
zdTable('bug')->gen(10);
zdTable('task')->config('task')->gen(10);
zdTable('kanbanlane')->config('kanbanlane')->gen(10);
zdTable('kanbancell')->config('kanbancell')->gen(30);

/**

title=测试 kanbanModel->buildExecutionCards();
timeout=0
cid=1

*/

$executionIdList = array(1, 2, 3);
$laneIdList      = array(1, 2, 3, 4, 5, 6, 7, 8 ,9);
$columnTypeList  = array('task', 'story', 'bug');
$cardIdList      = array(1, 2, 3, 4, 5);

$kanbanTester = new kanbanTest();
r($kanbanTester->buildExecutionCardsTest($executionIdList[0], $laneIdList[0], $columnTypeList[0], $cardIdList)) && p('cards:task:0')  && e('~~');  // 构造迭代的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[1], $laneIdList[1], $columnTypeList[0], $cardIdList)) && p('cards:task:0')  && e('N/A'); // 构造阶段的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[2], $laneIdList[2], $columnTypeList[0], $cardIdList)) && p('cards:task:0')  && e('N/A'); // 构造看板的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[0], $laneIdList[3], $columnTypeList[1], $cardIdList)) && p('cards:story:0') && e('N/A'); // 构造迭代的需求卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[1], $laneIdList[4], $columnTypeList[1], $cardIdList)) && p('cards:story:0') && e('N/A'); // 构造阶段的需求卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[2], $laneIdList[5], $columnTypeList[1], $cardIdList)) && p('cards:story:0') && e('N/A'); // 构造看板的需求卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[0], $laneIdList[6], $columnTypeList[2], $cardIdList)) && p('cards:bug:0')   && e('N/A'); // 构造迭代的Bug卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[1], $laneIdList[7], $columnTypeList[2], $cardIdList)) && p('cards:bug:0')   && e('N/A'); // 构造阶段的Bug卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[2], $laneIdList[8], $columnTypeList[2], $cardIdList)) && p('cards:bug:0')   && e('N/A'); // 构造看板的Bug卡片
