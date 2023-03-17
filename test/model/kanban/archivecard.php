#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->archiveCard();
cid=1
pid=1

测试归档卡片1 >> archived,0,1
测试归档卡片2 >> archived,0,1
测试归档卡片3 >> archived,0,1
测试归档卡片4 >> archived,0,1
测试归档卡片5 >> archived,0,1

*/

$cardIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->archiveCardTest($cardIDList[0])) && p('0:field,old,new') && e('archived,0,1'); // 测试归档卡片1
r($kanban->archiveCardTest($cardIDList[1])) && p('0:field,old,new') && e('archived,0,1'); // 测试归档卡片2
r($kanban->archiveCardTest($cardIDList[2])) && p('0:field,old,new') && e('archived,0,1'); // 测试归档卡片3
r($kanban->archiveCardTest($cardIDList[3])) && p('0:field,old,new') && e('archived,0,1'); // 测试归档卡片4
r($kanban->archiveCardTest($cardIDList[4])) && p('0:field,old,new') && e('archived,0,1'); // 测试归档卡片5
