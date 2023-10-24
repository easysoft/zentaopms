#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createRDLane();
cid=1
pid=1

测试创建执行101的执行看板的泳道 >> 6
测试创建执行102的执行看板的泳道 >> 6
测试创建执行103的执行看板的泳道 >> 6
测试创建执行104的执行看板的泳道 >> 6
测试创建执行105的执行看板的泳道 >> 6

*/

$executionIDList = array('161', '162', '163', '164', '165');
$regionIDList    = array('101', '102', '103', '104', '105');

$kanban = new kanbanTest();

r($kanban->createRDLaneTest($executionIDList[0], $regionIDList[0])) && p() && e('6'); // 测试创建执行101的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[1], $regionIDList[1])) && p() && e('6'); // 测试创建执行102的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[2], $regionIDList[2])) && p() && e('6'); // 测试创建执行103的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[3], $regionIDList[3])) && p() && e('6'); // 测试创建执行104的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[4], $regionIDList[4])) && p() && e('6'); // 测试创建执行105的执行看板的泳道
