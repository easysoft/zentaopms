#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanlane')->gen(5);

/**

title=测试 kanbanModel->refreshCards();
timeout=0
cid=1

- 刷新泳道101的卡片 @1:1,21,

- 刷新泳道102的卡片 @2:2,22,

- 刷新泳道103的卡片 @3:3,23,

- 刷新泳道104的卡片 @4:4,24,

- 刷新泳道105的卡片 @5:5,25,

*/

$laneIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->refreshCardsTest($laneIDList[0])) && p() && e('1:1,21,'); // 刷新泳道101的卡片
r($kanban->refreshCardsTest($laneIDList[1])) && p() && e('2:2,22,'); // 刷新泳道102的卡片
r($kanban->refreshCardsTest($laneIDList[2])) && p() && e('3:3,23,'); // 刷新泳道103的卡片
r($kanban->refreshCardsTest($laneIDList[3])) && p() && e('4:4,24,'); // 刷新泳道104的卡片
r($kanban->refreshCardsTest($laneIDList[4])) && p() && e('5:5,25,'); // 刷新泳道105的卡片