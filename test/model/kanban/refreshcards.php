#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

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

$laneIDList = array('101', '102', '103', '104', '105');

$kanban = new kanbanTest();

r($kanban->refreshCardsTest($laneIDList[0])) && p() && e('401; 402; 403; 404:244,; 405; 406; 407; 408; 409; 410; 411:');      // 刷新泳道101的卡片
r($kanban->refreshCardsTest($laneIDList[1])) && p() && e('412; 413; 414; 415; 416; 417; 418; 419; 420:181,182,183,');         // 刷新泳道102的卡片
r($kanban->refreshCardsTest($laneIDList[2])) && p() && e('421:781,61,; 422; 423:782,; 424:783,; 425; 426; 427:');             // 刷新泳道103的卡片
r($kanban->refreshCardsTest($laneIDList[3])) && p() && e('428; 429; 430; 431; 432; 433; 434:246,; 435; 436:248,; 437; 438:'); // 刷新泳道104的卡片
r($kanban->refreshCardsTest($laneIDList[4])) && p() && e('439; 440; 441; 442; 443; 444; 445; 446; 447:184,185,186,');         // 刷新泳道105的卡片
