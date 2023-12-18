#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(5);

/**

title=测试 kanbanModel->getKanbanIDByRegion();
timeout=0
cid=1

- 测试通过区域1获取看板id @1
- 测试通过区域2获取看板id @2
- 测试通过区域3获取看板id @3
- 测试通过区域4获取看板id @4
- 测试通过区域5获取看板id @5
- 测试通过不存在的区域获取看板id @0

*/
$regionIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getKanbanIDByRegionTest($regionIDList[0])) && p() && e('1'); // 测试通过区域1获取看板id
r($kanban->getKanbanIDByRegionTest($regionIDList[1])) && p() && e('2'); // 测试通过区域2获取看板id
r($kanban->getKanbanIDByRegionTest($regionIDList[2])) && p() && e('3'); // 测试通过区域3获取看板id
r($kanban->getKanbanIDByRegionTest($regionIDList[3])) && p() && e('4'); // 测试通过区域4获取看板id
r($kanban->getKanbanIDByRegionTest($regionIDList[4])) && p() && e('5'); // 测试通过区域5获取看板id
r($kanban->getKanbanIDByRegionTest($regionIDList[5])) && p() && e('0'); // 测试通过不存在的区域获取看板id