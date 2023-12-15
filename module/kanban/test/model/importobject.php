#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancell')->gen(1);
zdTable('kanbancard')->gen(1);

/**

title=测试 kanbanModel->importObject();
timeout=0
cid=1

- 测试导入卡片72 73 74到column 1属性cards @,2,3,4,1,2,801,
- 测试导入卡片75 77 77到column 5属性cards @,5,6,7,
- 测试导入卡片72 73 74到column 9属性cards @,8,9,10,
- 测试导入卡片75 77 77到column 13属性cards @,11,12,13,

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

r($kanban->importObjectTest($kanbanIDList[0], $regionIDList[0], $groupIDList[0], $columnIDList[0], $objectType[0], $param1)) && p('cards', '|') && e(',2,3,4,1,2,801,'); // 测试导入卡片72 73 74到column 1
r($kanban->importObjectTest($kanbanIDList[1], $regionIDList[1], $groupIDList[1], $columnIDList[1], $objectType[1], $param2)) && p('cards', '|') && e(',5,6,7,');         // 测试导入卡片75 77 77到column 5
r($kanban->importObjectTest($kanbanIDList[2], $regionIDList[2], $groupIDList[2], $columnIDList[2], $objectType[2], $param3)) && p('cards', '|') && e(',8,9,10,');        // 测试导入卡片72 73 74到column 9
r($kanban->importObjectTest($kanbanIDList[3], $regionIDList[3], $groupIDList[3], $columnIDList[3], $objectType[3], $param4)) && p('cards', '|') && e(',11,12,13,');      // 测试导入卡片75 77 77到column 13