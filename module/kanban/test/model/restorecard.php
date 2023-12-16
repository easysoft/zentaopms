#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(5);

/**

title=测试 kanbanModel->restoreCard();
timeout=0
cid=1

- 还原卡片1
 - 属性id @1
 - 属性name @卡片1
 - 属性archived @0
- 还原卡片1
 - 属性id @2
 - 属性name @卡片2
 - 属性archived @0
- 还原卡片2
 - 属性id @3
 - 属性name @卡片3
 - 属性archived @0
- 还原卡片3
 - 属性id @4
 - 属性name @卡片4
 - 属性archived @0
- 还原卡片4
 - 属性id @5
 - 属性name @卡片5
 - 属性archived @0

*/
$kanbanIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

$kanban->archiveCardTest($kanbanIDList[0]);
$kanban->archiveCardTest($kanbanIDList[1]);
$kanban->archiveCardTest($kanbanIDList[2]);
$kanban->archiveCardTest($kanbanIDList[3]);
$kanban->archiveCardTest($kanbanIDList[4]);

r($kanban->restoreCardTest($kanbanIDList[0])) && p('id,name,archived') && e('1,卡片1,0'); // 还原卡片1
r($kanban->restoreCardTest($kanbanIDList[1])) && p('id,name,archived') && e('2,卡片2,0'); // 还原卡片1
r($kanban->restoreCardTest($kanbanIDList[2])) && p('id,name,archived') && e('3,卡片3,0'); // 还原卡片2
r($kanban->restoreCardTest($kanbanIDList[3])) && p('id,name,archived') && e('4,卡片4,0'); // 还原卡片3
r($kanban->restoreCardTest($kanbanIDList[4])) && p('id,name,archived') && e('5,卡片5,0'); // 还原卡片4