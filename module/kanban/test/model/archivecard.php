#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(5);

/**

title=测试 kanbanModel->archiveCard();
timeout=0
cid=1

- 测试归档卡片1
 - 属性id @1
 - 属性name @卡片1
 - 属性archived @1
- 测试归档卡片2
 - 属性id @2
 - 属性name @卡片2
 - 属性archived @1
- 测试归档卡片3
 - 属性id @3
 - 属性name @卡片3
 - 属性archived @1
- 测试归档卡片4
 - 属性id @4
 - 属性name @卡片4
 - 属性archived @1
- 测试归档卡片5
 - 属性id @5
 - 属性name @卡片5
 - 属性archived @1

*/

$cardIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->archiveCardTest($cardIDList[0])) && p('id,name,archived') && e('1,卡片1,1'); // 测试归档卡片1
r($kanban->archiveCardTest($cardIDList[1])) && p('id,name,archived') && e('2,卡片2,1'); // 测试归档卡片2
r($kanban->archiveCardTest($cardIDList[2])) && p('id,name,archived') && e('3,卡片3,1'); // 测试归档卡片3
r($kanban->archiveCardTest($cardIDList[3])) && p('id,name,archived') && e('4,卡片4,1'); // 测试归档卡片4
r($kanban->archiveCardTest($cardIDList[4])) && p('id,name,archived') && e('5,卡片5,1'); // 测试归档卡片5