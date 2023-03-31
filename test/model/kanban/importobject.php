#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->importObject();
cid=1
pid=1

测试导入卡片72 73 74到column 1 >> ,1001,1002,1003,1,2,801,
测试导入卡片75 77 77到column 5 >> ,1004,1005,1006,9,10,809,
测试导入卡片72 73 74到column 9 >> ,1007,1008,1009,17,18,817,
测试导入卡片75 77 77到column 13 >> ,1010,1011,1012,25,26,825,

*/
$productplans  = array('72', '73', '74');
$releases      = array('75', '76', '77');
$executions    = array('82', '83', '84');
$builds        = array('85', '86', '87');

$targetLaneIDList = array('1', '2', '3', '4', '5');
$kanbanIDList     = array('1', '2', '3', '4', '5');
$regionIDList     = array('1', '2', '3', '4', '5');
$groupIDList      = array('1', '2', '3', '4', '5');
$columnIDList     = array('1', '5', '9', '13', '17');

$param1 = array('productplans' => $productplans, 'targetLane' => $targetLaneIDList[0]);
$param2 = array('releases' => $releases, 'targetLane' => $targetLaneIDList[1]);
$param3 = array('executions' => $executions, 'targetLane' => $targetLaneIDList[2]);
$param4 = array('builds' => $builds, 'targetLane' => $targetLaneIDList[3]);

$objectType = array('productplan', 'release', 'execution', 'build');

$kanban = new kanbanTest();

r($kanban->importObjectTest($kanbanIDList[0], $regionIDList[0], $groupIDList[0], $columnIDList[0], $objectType[0], $param1)) && p('cards') && e(',1001,1002,1003,1,2,801,');  // 测试导入卡片72 73 74到column 1
r($kanban->importObjectTest($kanbanIDList[1], $regionIDList[1], $groupIDList[1], $columnIDList[1], $objectType[1], $param2)) && p('cards') && e(',1004,1005,1006,9,10,809,'); // 测试导入卡片75 77 77到column 5
r($kanban->importObjectTest($kanbanIDList[2], $regionIDList[2], $groupIDList[2], $columnIDList[2], $objectType[2], $param3)) && p('cards') && e(',1007,1008,1009,17,18,817,');// 测试导入卡片72 73 74到column 9
r($kanban->importObjectTest($kanbanIDList[3], $regionIDList[3], $groupIDList[3], $columnIDList[3], $objectType[3], $param4)) && p('cards') && e(',1010,1011,1012,25,26,825,');// 测试导入卡片75 77 77到column 13
