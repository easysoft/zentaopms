#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getCardsByObject();
cid=1
pid=1

测试查询kanban 1的卡片数量 >> 16
测试查询kanban 2的卡片数量 >> 16
测试查询kanban 3的卡片数量 >> 16
测试查询kanban 4的卡片数量 >> 16
测试查询kanban 5的卡片数量 >> 16
测试查询不存在kanban的卡片数量 >> 0
测试查询region 1的卡片数量 >> 16
测试查询region 2的卡片数量 >> 16
测试查询region 3的卡片数量 >> 16
测试查询region 4的卡片数量 >> 16
测试查询region 5的卡片数量 >> 16
测试查询不存在region的卡片数量 >> 0
测试查询status doing的卡片数量 >> 667
测试查询status doing的卡片数量 >> 333

*/

$objectType   = array('kanban', 'region', 'status');
$kanbanIDList = array('1', '2', '3', '4', '5', '1000001');
$regionIDList = array('1', '2', '3', '4', '5', '1000001');
$statusList   = array('doing', 'done');

$kanban = new kanbanTest();

r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[0])) && p() && e('16');   // 测试查询kanban 1的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[1])) && p() && e('16');   // 测试查询kanban 2的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[2])) && p() && e('16');   // 测试查询kanban 3的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[3])) && p() && e('16');   // 测试查询kanban 4的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[4])) && p() && e('16');   // 测试查询kanban 5的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[5])) && p() && e('0');   // 测试查询不存在kanban的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[0])) && p() && e('16');   // 测试查询region 1的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[1])) && p() && e('16');   // 测试查询region 2的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[2])) && p() && e('16');   // 测试查询region 3的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[3])) && p() && e('16');   // 测试查询region 4的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[4])) && p() && e('16');   // 测试查询region 5的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[5])) && p() && e('0');   // 测试查询不存在region的卡片数量
r($kanban->getCardsByObjectTest($objectType[2], $statusList[0]))   && p() && e('667'); // 测试查询status doing的卡片数量
r($kanban->getCardsByObjectTest($objectType[2], $statusList[1]))   && p() && e('333'); // 测试查询status doing的卡片数量