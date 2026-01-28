#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('kanbanlane')->loadYaml('rdkanbanlane')->gen(5);
zenData('kanbancell')->loadYaml('rdkanbancell')->gen(5);
zenData('kanbanregion')->loadYaml('rdkanbanregion')->gen(5);
zenData('task')->loadYaml('task')->gen(5);

/**

title=测试 kanbanModel->updateLane();
timeout=0
cid=16965

- 测试更新执行101的story泳道 @0
- 测试更新执行101的bug泳道 @0
- 测试更新执行101的task泳道 @,1,21,
- 测试更新执行102的story泳道 @0
- 测试更新执行102的bug泳道 @0
- 测试更新执行102的task泳道 @,2,22,
- 测试更新执行103的story泳道 @0
- 测试更新执行103的bug泳道 @0
- 测试更新执行103的task泳道 @,3,23,

*/

$executionID = array('1', '2', '3');
$laneType    = array('story', 'bug', 'task');

$kanban = new kanbanModelTest();

r($kanban->updateLaneTest($executionID[0], $laneType[0])) && p('', '|') && e('0');      // 测试更新执行101的story泳道
r($kanban->updateLaneTest($executionID[0], $laneType[1])) && p('', '|') && e('0');      // 测试更新执行101的bug泳道
r($kanban->updateLaneTest($executionID[0], $laneType[2])) && p('', '|') && e(',1,21,'); // 测试更新执行101的task泳道
r($kanban->updateLaneTest($executionID[1], $laneType[0])) && p('', '|') && e('0');      // 测试更新执行102的story泳道
r($kanban->updateLaneTest($executionID[1], $laneType[1])) && p('', '|') && e('0');      // 测试更新执行102的bug泳道
r($kanban->updateLaneTest($executionID[1], $laneType[2])) && p('', '|') && e(',2,22,'); // 测试更新执行102的task泳道
r($kanban->updateLaneTest($executionID[2], $laneType[0])) && p('', '|') && e('0');      // 测试更新执行103的story泳道
r($kanban->updateLaneTest($executionID[2], $laneType[1])) && p('', '|') && e('0');      // 测试更新执行103的bug泳道
r($kanban->updateLaneTest($executionID[2], $laneType[2])) && p('', '|') && e(',3,23,'); // 测试更新执行103的task泳道