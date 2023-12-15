#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancell')->gen(10);

/**

title=测试 kanbanModel->moveCard();
timeout=0
cid=1

- 将卡片1从泳道1 列1转移到泳道1 列2属性cards @,3,4,803,1,
- 将卡片2从泳道1 列1转移到泳道1 列2属性cards @,3,4,803,1,2, 
- 将卡片3从泳道1 列2转移到泳道1 列3属性cards @,5,6,805,3,
- 将卡片4从泳道1 列2转移到泳道1 列3属性cards @,5,6,805,3,4,
- 将卡片5从泳道1 列3转移到泳道1 列1属性cards @,801,5,
- 将卡片6从泳道1 列3转移到泳道1 列1属性cards @,801,5,6,

*/
$cardIDList   = array('1', '2', '3', '4', '5', '6');
$columnIDList = array('1', '2', '3');

$laneID   = 1;
$kanbanID = 1;

$kanban = new kanbanTest();

r($kanban->moveCardTest($cardIDList[0], $columnIDList[0], $columnIDList[1], $laneID, $laneID, $kanbanID)) && p('cards', '|') && e(',3,4,803,1,');    // 将卡片1从泳道1 列1转移到泳道1 列2
r($kanban->moveCardTest($cardIDList[1], $columnIDList[0], $columnIDList[1], $laneID, $laneID, $kanbanID)) && p('cards', '|') && e(',3,4,803,1,2, '); // 将卡片2从泳道1 列1转移到泳道1 列2
r($kanban->moveCardTest($cardIDList[2], $columnIDList[1], $columnIDList[2], $laneID, $laneID, $kanbanID)) && p('cards', '|') && e(',5,6,805,3,');    // 将卡片3从泳道1 列2转移到泳道1 列3
r($kanban->moveCardTest($cardIDList[3], $columnIDList[1], $columnIDList[2], $laneID, $laneID, $kanbanID)) && p('cards', '|') && e(',5,6,805,3,4,');  // 将卡片4从泳道1 列2转移到泳道1 列3
r($kanban->moveCardTest($cardIDList[4], $columnIDList[2], $columnIDList[0], $laneID, $laneID, $kanbanID)) && p('cards', '|') && e(',801,5,');        // 将卡片5从泳道1 列3转移到泳道1 列1
r($kanban->moveCardTest($cardIDList[5], $columnIDList[2], $columnIDList[0], $laneID, $laneID, $kanbanID)) && p('cards', '|') && e(',801,5,6,');      // 将卡片6从泳道1 列3转移到泳道1 列1