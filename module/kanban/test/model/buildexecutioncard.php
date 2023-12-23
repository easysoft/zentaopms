#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(10);
zdTable('kanbancard')->gen(10);
zdTable('task')->gen(10);

/**

title=测试 kanbanModel->buildExecutionCard();
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
- 构造迭代的任务卡片
 - 属性id @1
 - 属性title @任务1
- 构造阶段的任务卡片
 - 属性id @2
 - 属性title @任务2
- 构造看板的任务卡片
 - 属性id @3
 - 属性title @任务3
- 构造迭代的任务卡片
 - 属性id @1
 - 属性title @任务1
- 构造阶段的任务卡片
 - 属性id @2
 - 属性title @任务2
- 构造看板的任务卡片
 - 属性id @3
 - 属性title @任务3

*/

$cardIdList   = array(1, 2, 3, 4, 5);
$columnIdList = array(1, 2, 3, 4, 5);
$laneType     = array('story', 'bug', 'task');

$kanbanTester = new kanbanTest();

r($kanbanTester->buildExecutionCardTest($cardIdList[0], $columnIdList[0], $laneType[0], ''))     && p('id,title') && e('1,``');         // 构造看板卡片，查看id和title
r($kanbanTester->buildExecutionCardTest($cardIdList[1], $columnIdList[1], $laneType[1], '测试')) && p('id,title') && e('0,0');          // 构造看板卡片，查看id和title
r($kanbanTester->buildExecutionCardTest($cardIdList[2], $columnIdList[2], $laneType[2], ''))     && p('id,title') && e('3,开发任务13'); // 构造看板卡片，查看id和title
r($kanbanTester->buildExecutionCardTest($cardIdList[3], $columnIdList[3], $laneType[0], 'test')) && p('id,title') && e('0,0');          // 构造看板卡片，查看id和title
r($kanbanTester->buildExecutionCardTest($cardIdList[4], $columnIdList[4], $laneType[1], ''))     && p('id,title') && e('5,``');         // 构造看板卡片，查看id和title
