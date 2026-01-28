#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(180);
zenData('kanbanregion')->gen(0);
zenData('kanbancolumn')->gen(0);
zenData('kanbanlane')->gen(0);

/**

title=测试 kanbanModel->createRDLane();
timeout=0
cid=16898

- 测试创建执行101的执行看板的泳道 @4
- 测试创建执行102的执行看板的泳道 @4
- 测试创建执行103的执行看板的泳道 @4
- 测试创建执行104的执行看板的泳道 @4
- 测试创建执行105的执行看板的泳道 @4

*/

$executionIDList = array('161', '162', '163', '164', '165');
$regionIDList    = array('101', '102', '103', '104', '105');

$kanban = new kanbanModelTest();

r($kanban->createRDLaneTest($executionIDList[0], $regionIDList[0])) && p() && e('4'); // 测试创建执行101的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[1], $regionIDList[1])) && p() && e('4'); // 测试创建执行102的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[2], $regionIDList[2])) && p() && e('4'); // 测试创建执行103的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[3], $regionIDList[3])) && p() && e('4'); // 测试创建执行104的执行看板的泳道
r($kanban->createRDLaneTest($executionIDList[4], $regionIDList[4])) && p() && e('4'); // 测试创建执行105的执行看板的泳道