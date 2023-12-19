#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('kanbanexecution')->gen(5);
zdTable('kanbanregion')->config('rdkanbanregion')->gen(5);
zdTable('kanbangroup')->config('rdkanbangroup')->gen(20);
zdTable('kanbancolumn')->gen(20);
zdTable('kanbanlane')->config('rdkanbanlane')->gen(10);
zdTable('kanbancell')->config('rdkanbancell')->gen(20);
zdTable('task')->config('rdkanbantask')->gen(20);

/**

title=测试 kanbanModel->buildExecutionCards();
timeout=0
cid=1

- 构造迭代的任务卡片
 - 属性id @1
 - 属性title @任务1
- 构造阶段的任务卡片
 - 属性id @2
 - 属性title @任务2
- 构造看板的任务卡片
 - 属性id @3
 - 属性title @任务3
- 构造迭代的任务卡片 @0
- 构造阶段的任务卡片 @0
- 构造看板的任务卡片 @0
- 构造迭代的任务卡片 @0
- 构造阶段的任务卡片 @0
- 构造看板的任务卡片 @0

*/

$executionIdList = array(1, 2, 3);
$laneIdList      = array(1, 2, 3);
$colIdList       = array(1, 2, 3);
$cardIdList      = array(1, 2, 3, 4, 5);

$kanbanTester = new kanbanTest();
r($kanbanTester->buildExecutionCardsTest($executionIdList[0], $laneIdList[0], $colIdList[0], $cardIdList)[1][1][0]) && p('id,title') && e('1,任务1'); // 构造迭代的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[1], $laneIdList[1], $colIdList[0], $cardIdList)[2][1][0]) && p('id,title') && e('2,任务2'); // 构造阶段的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[2], $laneIdList[2], $colIdList[0], $cardIdList)[3][1][0]) && p('id,title') && e('3,任务3'); // 构造看板的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[0], $laneIdList[0], $colIdList[1], $cardIdList)[1][1][0]) && p('')         && e('0');       // 构造迭代的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[1], $laneIdList[1], $colIdList[1], $cardIdList)[2][1][0]) && p('')         && e('0');       // 构造阶段的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[2], $laneIdList[2], $colIdList[1], $cardIdList)[3][1][0]) && p('')         && e('0');       // 构造看板的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[0], $laneIdList[0], $colIdList[2], $cardIdList)[1][1][0]) && p('')         && e('0');       // 构造迭代的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[1], $laneIdList[1], $colIdList[2], $cardIdList)[2][1][0]) && p('')         && e('0');       // 构造阶段的任务卡片
r($kanbanTester->buildExecutionCardsTest($executionIdList[2], $laneIdList[2], $colIdList[2], $cardIdList)[3][1][0]) && p('')         && e('0');       // 构造看板的任务卡片