#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(0);
zdTable('kanbancolumn')->gen(0);
zdTable('kanbanlane')->gen(0);

/**

title=测试 kanbanModel->createRDLane();
timeout=0
cid=1

- 测试创建执行101的执行看板的泳道 @3
- 测试创建执行102的执行看板的泳道 @3
- 测试创建执行103的执行看板的泳道 @3
- 测试创建执行104的执行看板的泳道 @3
- 测试创建执行105的执行看板的泳道 @3

*/

$executionIDList = array('161', '162', '163', '164', '165');
$regionIDList    = array('101', '102', '103', '104', '105');

$kanban = new kanbanTest();

r($kanban->createRDLaneTest($executionIDList[0], $regionIDList[0])) && p() && e('3'); // 测试创建执行101的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[1], $regionIDList[1])) && p() && e('3'); // 测试创建执行102的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[2], $regionIDList[2])) && p() && e('3'); // 测试创建执行103的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[3], $regionIDList[3])) && p() && e('3'); // 测试创建执行104的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[4], $regionIDList[4])) && p() && e('3'); // 测试创建执行105的执行看板的泳道