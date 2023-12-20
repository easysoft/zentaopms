#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(20);

/**

title=测试 kanbanModel->getCardsByObject();
timeout=0
cid=1

- 测试查询kanban 1的卡片数量 @8
- 测试查询kanban 2的卡片数量 @8
- 测试查询kanban 3的卡片数量 @0
- 测试查询region 1的卡片数量 @8
- 测试查询region 2的卡片数量 @8
- 测试查询region 3的卡片数量 @0
- 测试查询status doing的卡片数量 @14
- 测试查询status doing的卡片数量 @6

*/

$objectType   = array('kanban', 'region', 'status');
$kanbanIDList = array(1, 2, 1000001);
$regionIDList = array(1, 2, 1000001);
$statusList   = array('doing', 'done');

$kanban = new kanbanTest();

r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[0])) && p() && e('8');  // 测试查询kanban 1的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[1])) && p() && e('8');  // 测试查询kanban 2的卡片数量
r($kanban->getCardsByObjectTest($objectType[0], $kanbanIDList[2])) && p() && e('0');  // 测试查询kanban 3的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[0])) && p() && e('8');  // 测试查询region 1的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[1])) && p() && e('8');  // 测试查询region 2的卡片数量
r($kanban->getCardsByObjectTest($objectType[1], $regionIDList[2])) && p() && e('0');  // 测试查询region 3的卡片数量
r($kanban->getCardsByObjectTest($objectType[2], $statusList[0]))   && p() && e('14'); // 测试查询status doing的卡片数量
r($kanban->getCardsByObjectTest($objectType[2], $statusList[1]))   && p() && e('6');  // 测试查询status doing的卡片数量