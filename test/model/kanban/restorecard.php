#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->restoreCard();
cid=1
pid=1

还原卡片1 >> archived,1,0
还原卡片1 >> archived,1,0
还原卡片2 >> archived,1,0
还原卡片3 >> archived,1,0
还原卡片4 >> archived,1,0

*/
$kanbanIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

$kanban->archiveCardTest($kanbanIDList[0]);
$kanban->archiveCardTest($kanbanIDList[1]);
$kanban->archiveCardTest($kanbanIDList[2]);
$kanban->archiveCardTest($kanbanIDList[3]);
$kanban->archiveCardTest($kanbanIDList[4]);

r($kanban->restoreCardTest($kanbanIDList[0])) && p('0:field,old,new') && e('archived,1,0'); // 还原卡片1
r($kanban->restoreCardTest($kanbanIDList[1])) && p('0:field,old,new') && e('archived,1,0'); // 还原卡片1
r($kanban->restoreCardTest($kanbanIDList[2])) && p('0:field,old,new') && e('archived,1,0'); // 还原卡片2
r($kanban->restoreCardTest($kanbanIDList[3])) && p('0:field,old,new') && e('archived,1,0'); // 还原卡片3
r($kanban->restoreCardTest($kanbanIDList[4])) && p('0:field,old,new') && e('archived,1,0'); // 还原卡片4