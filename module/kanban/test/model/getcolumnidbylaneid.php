#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->config('rdkanbancolumn')->gen(10);
zdTable('kanbancell')->config('rdkanbancell')->gen(10);

/**

title=测试 kanbanModel->getColumnIDByLaneID();
timeout=0
cid=1

- 测试获取泳道1 column1的看板列 @1
- 测试获取泳道1 column2的看板列 @0
- 测试获取泳道1 wait的看板列 @0
- 测试获取泳道1 test的看板列 @0
- 测试获取泳道1 backlog的看板列 @0
- 测试获取泳道2 column2的看板列 @2
- 测试获取泳道2 wait的看板列 @0
- 测试获取泳道107 wait的看板列 @0
- 测试获取泳道107 test的看板列 @0
- 测试获取泳道107 backlog的看板列 @0
- 测试获取泳道108 wait的看板列 @0
- 测试获取泳道108 test的看板列 @0
- 测试获取泳道108 backlog的看板列 @0
- 测试获取泳道109 wait的看板列 @0
- 测试获取泳道109 test的看板列 @0
- 测试获取泳道109 backlog的看板列 @0
- 测试获取不存在的泳道 common的看板列 @0

*/

$laneIDList     = array('1', '2', '107', '108', '109', '10001');
$columnTypeList = array('column1', 'column2', 'wait', 'test', 'backlog');

$kanban = new kanbanTest();

r($kanban->getColumnIDByLaneIDTest($laneIDList[0], $columnTypeList[0])) && p() && e('1'); // 测试获取泳道1 column1的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[0], $columnTypeList[1])) && p() && e('0'); // 测试获取泳道1 column2的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[0], $columnTypeList[2])) && p() && e('0'); // 测试获取泳道1 wait的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[0], $columnTypeList[3])) && p() && e('0'); // 测试获取泳道1 test的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[0], $columnTypeList[4])) && p() && e('0'); // 测试获取泳道1 backlog的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[1], $columnTypeList[1])) && p() && e('2'); // 测试获取泳道2 column2的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[1], $columnTypeList[2])) && p() && e('0'); // 测试获取泳道2 wait的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[2], $columnTypeList[2])) && p() && e('0'); // 测试获取泳道107 wait的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[2], $columnTypeList[3])) && p() && e('0'); // 测试获取泳道107 test的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[2], $columnTypeList[4])) && p() && e('0'); // 测试获取泳道107 backlog的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[3], $columnTypeList[2])) && p() && e('0'); // 测试获取泳道108 wait的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[3], $columnTypeList[3])) && p() && e('0'); // 测试获取泳道108 test的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[3], $columnTypeList[4])) && p() && e('0'); // 测试获取泳道108 backlog的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[4], $columnTypeList[2])) && p() && e('0'); // 测试获取泳道109 wait的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[4], $columnTypeList[3])) && p() && e('0'); // 测试获取泳道109 test的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[4], $columnTypeList[4])) && p() && e('0'); // 测试获取泳道109 backlog的看板列
r($kanban->getColumnIDByLaneIDTest($laneIDList[5], $columnTypeList[0])) && p() && e('0'); // 测试获取不存在的泳道 common的看板列