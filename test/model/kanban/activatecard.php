#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->activateCard();
cid=1
pid=1

测试激活卡片1，进度0 >> 1,卡片1,doing,0
测试激活卡片1，进度50 >> 1,卡片1,doing,50
测试激活卡片1，进度100 >> 请输入正确的进度
测试激活卡片1，进度101 >> 请输入正确的进度
测试激活卡片1，进度-1 >> 请输入正确的进度
测试激活卡片2，进度0 >> 2,卡片2,doing,0
测试激活卡片3，进度0 >> 3,卡片3,doing,50
测试激活卡片4，进度0 >> 4,卡片4,doing,0
测试激活卡片5，进度0 >> 5,卡片5,doing,50
测试激活不存在的卡片，进度0 >> 0

*/

$cardIDList   = array('1', '2', '3', '4', '5', '100001');
$progressList = array('0', '50', '100', '101', '-1');

$kanban = new kanbanTest();

r($kanban->activateCardTest($cardIDList[0], $progressList[0])) && p('id,name,status,progress') && e('1,卡片1,doing,0');  // 测试激活卡片1，进度0
r($kanban->activateCardTest($cardIDList[0], $progressList[1])) && p('id,name,status,progress') && e('1,卡片1,doing,50'); // 测试激活卡片1，进度50
r($kanban->activateCardTest($cardIDList[0], $progressList[2])) && p('0:')                      && e('请输入正确的进度'); // 测试激活卡片1，进度100
r($kanban->activateCardTest($cardIDList[0], $progressList[3])) && p('0:')                      && e('请输入正确的进度'); // 测试激活卡片1，进度101
r($kanban->activateCardTest($cardIDList[0], $progressList[4])) && p('0:')                      && e('请输入正确的进度'); // 测试激活卡片1，进度-1
r($kanban->activateCardTest($cardIDList[1], $progressList[0])) && p('id,name,status,progress') && e('2,卡片2,doing,0');  // 测试激活卡片2，进度0
r($kanban->activateCardTest($cardIDList[2], $progressList[1])) && p('id,name,status,progress') && e('3,卡片3,doing,50'); // 测试激活卡片3，进度0
r($kanban->activateCardTest($cardIDList[3], $progressList[0])) && p('id,name,status,progress') && e('4,卡片4,doing,0');  // 测试激活卡片4，进度0
r($kanban->activateCardTest($cardIDList[4], $progressList[1])) && p('id,name,status,progress') && e('5,卡片5,doing,50'); // 测试激活卡片5，进度0
r($kanban->activateCardTest($cardIDList[5], $progressList[0])) && p('id,name,status,progress') && e('0');                // 测试激活不存在的卡片，进度0
