#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';
su('admin');

zenData('kanbanlane')->gen(5);
zenData('kanbancell')->gen(20);

/**

title=测试 kanbanModel->refreshCards();
timeout=0
cid=1

- 刷新泳道101的卡片 @1:1,2,801,; 2:3,4,803,; 3:5,6,805,; 4:7,8,807,
- 刷新泳道102的卡片 @5:9,10,809,; 6:11,12,811,; 7:13,14,813,; 8:15,16,815,
- 刷新泳道103的卡片 @9:17,18,817,; 10:19,20,819,; 11:21,22,821,; 12:23,24,823,
- 刷新泳道104的卡片 @13:25,26,825,; 14:27,28,827,; 15:29,30,829,; 16:31,32,831,
- 刷新泳道105的卡片 @17:33,34,833,; 18:35,36,835,; 19:37,38,837,; 20:39,40,839,

*/

$laneIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->refreshCardsTest($laneIDList[0])) && p('', '|') && e('1:1,2,801,; 2:3,4,803,; 3:5,6,805,; 4:7,8,807,');             // 刷新泳道101的卡片
r($kanban->refreshCardsTest($laneIDList[1])) && p('', '|') && e('5:9,10,809,; 6:11,12,811,; 7:13,14,813,; 8:15,16,815,');      // 刷新泳道102的卡片
r($kanban->refreshCardsTest($laneIDList[2])) && p('', '|') && e('9:17,18,817,; 10:19,20,819,; 11:21,22,821,; 12:23,24,823,');  // 刷新泳道103的卡片
r($kanban->refreshCardsTest($laneIDList[3])) && p('', '|') && e('13:25,26,825,; 14:27,28,827,; 15:29,30,829,; 16:31,32,831,'); // 刷新泳道104的卡片
r($kanban->refreshCardsTest($laneIDList[4])) && p('', '|') && e('17:33,34,833,; 18:35,36,835,; 19:37,38,837,; 20:39,40,839,'); // 刷新泳道105的卡片