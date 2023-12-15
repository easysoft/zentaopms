#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(5);

/**

title=测试 kanbanModel->activateCard();
timeout=0
cid=1

- 测试激活卡片1，进度0
 - 属性id @1
 - 属性name @卡片1
 - 属性status @doing
 - 属性progress @0
- 测试激活卡片1，进度50
 - 属性id @1
 - 属性name @卡片1
 - 属性status @doing
 - 属性progress @50
- 测试激活卡片1，进度100 @请输入正确的进度
- 测试激活卡片1，进度101 @请输入正确的进度
- 测试激活卡片1，进度-1 @请输入正确的进度
- 测试激活卡片2，进度0
 - 属性id @2
 - 属性name @卡片2
 - 属性status @doing
 - 属性progress @0
- 测试激活卡片3，进度0
 - 属性id @3
 - 属性name @卡片3
 - 属性status @doing
 - 属性progress @50
- 测试激活卡片4，进度0
 - 属性id @4
 - 属性name @卡片4
 - 属性status @doing
 - 属性progress @0
- 测试激活卡片5，进度0
 - 属性id @5
 - 属性name @卡片5
 - 属性status @doing
 - 属性progress @50

*/

$cardIDList   = array('1', '2', '3', '4', '5');
$progressList = array('0', '50', '100', '101', '-1');

$kanban = new kanbanTest();

r($kanban->activateCardTest($cardIDList[0], $progressList[0])) && p('id,name,status,progress') && e('1,卡片1,doing,0');  // 测试激活卡片1，进度0
r($kanban->activateCardTest($cardIDList[0], $progressList[1])) && p('id,name,status,progress') && e('1,卡片1,doing,50'); // 测试激活卡片1，进度50
r($kanban->activateCardTest($cardIDList[0], $progressList[2])) && p('0')                      && e('请输入正确的进度');  // 测试激活卡片1，进度100
r($kanban->activateCardTest($cardIDList[0], $progressList[3])) && p('0')                      && e('请输入正确的进度');  // 测试激活卡片1，进度101
r($kanban->activateCardTest($cardIDList[0], $progressList[4])) && p('0')                      && e('请输入正确的进度');  // 测试激活卡片1，进度-1
r($kanban->activateCardTest($cardIDList[1], $progressList[0])) && p('id,name,status,progress') && e('2,卡片2,doing,0');  // 测试激活卡片2，进度0
r($kanban->activateCardTest($cardIDList[2], $progressList[1])) && p('id,name,status,progress') && e('3,卡片3,doing,50'); // 测试激活卡片3，进度0
r($kanban->activateCardTest($cardIDList[3], $progressList[0])) && p('id,name,status,progress') && e('4,卡片4,doing,0');  // 测试激活卡片4，进度0
r($kanban->activateCardTest($cardIDList[4], $progressList[1])) && p('id,name,status,progress') && e('5,卡片5,doing,50'); // 测试激活卡片5，进度0