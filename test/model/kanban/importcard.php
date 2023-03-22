#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->importCard();
cid=1
pid=1

测试导入卡片72 73 74到column 1 >> ,72,73,74,1,2,801,
测试导入卡片75 77 77到column 5 >> ,75,76,77,9,10,809,
测试导入卡片72 73 74到column 9 >> ,82,83,84,17,18,817,
测试导入卡片75 77 77到column 13 >> ,85,86,87,25,26,825,
测试导入卡片72 73 74到column 17 >> ,95,96,97,33,34,833,

*/

$cards1 = array('72', '73', '74');
$cards2 = array('75', '76', '77');
$cards3 = array('82', '83', '84');
$cards4 = array('85', '86', '87');
$cards5 = array('95', '96', '97');

$targetLaneIDList = array('1', '2', '3', '4', '5');
$kanbanIDList     = array('1', '2', '3', '4', '5');
$regionIDList     = array('1', '2', '3', '4', '5');
$groupIDList      = array('1', '2', '3', '4', '5');
$columnIDList     = array('1', '5', '9', '13', '17');

$kanban = new kanbanTest();

r($kanban->importCardTest($kanbanIDList[0], $regionIDList[0], $groupIDList[0], $columnIDList[0], $cards1, $targetLaneIDList[0])) && p('cards') && e(',72,73,74,1,2,801,');  // 测试导入卡片72 73 74到column 1
r($kanban->importCardTest($kanbanIDList[1], $regionIDList[1], $groupIDList[1], $columnIDList[1], $cards2, $targetLaneIDList[1])) && p('cards') && e(',75,76,77,9,10,809,'); // 测试导入卡片75 77 77到column 5
r($kanban->importCardTest($kanbanIDList[2], $regionIDList[2], $groupIDList[2], $columnIDList[2], $cards3, $targetLaneIDList[2])) && p('cards') && e(',82,83,84,17,18,817,');// 测试导入卡片72 73 74到column 9
r($kanban->importCardTest($kanbanIDList[3], $regionIDList[3], $groupIDList[3], $columnIDList[3], $cards4, $targetLaneIDList[3])) && p('cards') && e(',85,86,87,25,26,825,');// 测试导入卡片75 77 77到column 13
r($kanban->importCardTest($kanbanIDList[4], $regionIDList[4], $groupIDList[4], $columnIDList[4], $cards5, $targetLaneIDList[4])) && p('cards') && e(',95,96,97,33,34,833,');// 测试导入卡片72 73 74到column 17
