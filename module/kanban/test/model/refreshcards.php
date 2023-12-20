#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanlane')->gen(5);
zdTable('company')->gen(1);
/**

title=测试 kanbanModel->refreshCards();
cid=1
pid=1

刷新泳道101的卡片 >> 401; 402; 403; 404:244,; 405; 406; 407; 408; 409; 410; 411:
刷新泳道102的卡片 >> 412; 413; 414; 415; 416; 417; 418; 419; 420:181,182,183,
刷新泳道103的卡片 >> 421:781,61,; 422; 423:782,; 424:783,; 425; 426; 427:
刷新泳道104的卡片 >> 428; 429; 430; 431; 432; 433; 434:246,; 435; 436:248,; 437; 438:
刷新泳道105的卡片 >> 439; 440; 441; 442; 443; 444; 445; 446; 447:184,185,186,

*/

$laneIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->refreshCardsTest($laneIDList[0])) && p() && e('1:1,21,'); // 刷新泳道101的卡片
r($kanban->refreshCardsTest($laneIDList[1])) && p() && e('2:2,22,'); // 刷新泳道102的卡片
r($kanban->refreshCardsTest($laneIDList[2])) && p() && e('3:3,23,'); // 刷新泳道103的卡片
r($kanban->refreshCardsTest($laneIDList[3])) && p() && e('4:4,24,'); // 刷新泳道104的卡片
r($kanban->refreshCardsTest($laneIDList[4])) && p() && e('5:5,25,'); // 刷新泳道105的卡片
