#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getCardByID();
cid=1
pid=1

测试查询卡片1的信息 >> 卡片1,doing,3,0
测试查询卡片2的信息 >> 卡片2,doing,3,50
测试查询卡片3的信息 >> 卡片3,done,3,100
测试查询卡片4的信息 >> 卡片4,doing,3,0
测试查询卡片5的信息 >> 卡片5,doing,3,50
测试查询不存在卡片的信息 >> 0

*/

$cardIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getCardByIDTest($cardIDList[0])) && p('name,status,pri,progress') && e('卡片1,doing,3,0');  // 测试查询卡片1的信息
r($kanban->getCardByIDTest($cardIDList[1])) && p('name,status,pri,progress') && e('卡片2,doing,3,50'); // 测试查询卡片2的信息
r($kanban->getCardByIDTest($cardIDList[2])) && p('name,status,pri,progress') && e('卡片3,done,3,100'); // 测试查询卡片3的信息
r($kanban->getCardByIDTest($cardIDList[3])) && p('name,status,pri,progress') && e('卡片4,doing,3,0');  // 测试查询卡片4的信息
r($kanban->getCardByIDTest($cardIDList[4])) && p('name,status,pri,progress') && e('卡片5,doing,3,50'); // 测试查询卡片5的信息
r($kanban->getCardByIDTest($cardIDList[5])) && p()                           && e('0');                // 测试查询不存在卡片的信息