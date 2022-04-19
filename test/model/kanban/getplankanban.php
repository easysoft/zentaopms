#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getPlanKanban();
cid=1
pid=1

测试获取产品1的计划看板 >> lanes:1, cards:3
测试获取产品2的计划看板 >> lanes:1, cards:3
测试获取产品3的计划看板 >> lanes:1, cards:3
测试获取产品4的计划看板 >> lanes:3, cards:2
测试获取产品4 分支1的计划看板 >> lanes:1, cards:1
测试获取产品5的计划看板 >> lanes:3, cards:2
测试获取产品5 分支3的计划看板 >> lanes:1, cards:1

*/

$productIDList = array('1', '2', '3', '41', '42');
$branchIDList  = array('0', '1', '3');

$kanban = new kanbanTest();

r($kanban->getPlanKanbanTest($productIDList[0]))                   && p() && e('lanes:1, cards:3'); // 测试获取产品1的计划看板
r($kanban->getPlanKanbanTest($productIDList[1]))                   && p() && e('lanes:1, cards:3'); // 测试获取产品2的计划看板
r($kanban->getPlanKanbanTest($productIDList[2]))                   && p() && e('lanes:1, cards:3'); // 测试获取产品3的计划看板
r($kanban->getPlanKanbanTest($productIDList[3]))                   && p() && e('lanes:3, cards:2'); // 测试获取产品4的计划看板
r($kanban->getPlanKanbanTest($productIDList[3], $branchIDList[1])) && p() && e('lanes:1, cards:1'); // 测试获取产品4 分支1的计划看板
r($kanban->getPlanKanbanTest($productIDList[4]))                   && p() && e('lanes:3, cards:2'); // 测试获取产品5的计划看板
r($kanban->getPlanKanbanTest($productIDList[4], $branchIDList[2])) && p() && e('lanes:1, cards:1'); // 测试获取产品5 分支3的计划看板