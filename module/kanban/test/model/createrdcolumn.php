#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(0);
zdTable('kanbancolumn')->gen(0);
zdTable('kanbanlane')->gen(0);

/**

title=测试 kanbanModel->createRDColumn();
timeout=0
cid=1

- 测试创建执行161 区域101 泳道组101 泳道101 story执行看板的泳道列 @11
- 测试创建执行161 区域101 泳道组102 泳道102 bug执行看板的泳道列 @9
- 测试创建执行161 区域101 泳道组103 泳道103 task执行看板的泳道列 @7
- 测试创建执行162 区域102 泳道组104 泳道104 story执行看板的泳道列 @11
- 测试创建执行162 区域102 泳道组105 泳道105 bug执行看板的泳道列 @9
- 测试创建执行162 区域102 泳道组106 泳道106 task执行看板的泳道列 @7

*/

$regionIDList    = array('101', '102');
$groupIDList     = array('101', '102', '103', '104', '105', '106');
$laneIDList      = array('101', '102', '103', '104', '105', '106');
$laneTypeList    = array('story', 'bug', 'task');
$executionIDList = array('161', '162');

$kanban = new kanbanTest();

r($kanban->createRDColumnTest($regionIDList[0], $groupIDList[0], $laneIDList[0], $laneTypeList[0], $executionIDList[0])) && p() && e('11'); // 测试创建执行161 区域101 泳道组101 泳道101 story执行看板的泳道列
r($kanban->createRDColumnTest($regionIDList[0], $groupIDList[1], $laneIDList[1], $laneTypeList[1], $executionIDList[0])) && p() && e('9');  // 测试创建执行161 区域101 泳道组102 泳道102 bug执行看板的泳道列
r($kanban->createRDColumnTest($regionIDList[0], $groupIDList[2], $laneIDList[2], $laneTypeList[2], $executionIDList[0])) && p() && e('7');  // 测试创建执行161 区域101 泳道组103 泳道103 task执行看板的泳道列
r($kanban->createRDColumnTest($regionIDList[1], $groupIDList[3], $laneIDList[3], $laneTypeList[0], $executionIDList[1])) && p() && e('11'); // 测试创建执行162 区域102 泳道组104 泳道104 story执行看板的泳道列
r($kanban->createRDColumnTest($regionIDList[1], $groupIDList[4], $laneIDList[4], $laneTypeList[1], $executionIDList[1])) && p() && e('9');  // 测试创建执行162 区域102 泳道组105 泳道105 bug执行看板的泳道列
r($kanban->createRDColumnTest($regionIDList[1], $groupIDList[5], $laneIDList[5], $laneTypeList[2], $executionIDList[1])) && p() && e('7');  // 测试创建执行162 区域102 泳道组106 泳道106 task执行看板的泳道列