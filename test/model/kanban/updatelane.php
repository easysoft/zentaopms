#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->updateLane();
cid=1
pid=1

测试更新执行101的story泳道 >> 0
测试更新执行101的bug泳道 >> ,1,3,301,2,
测试更新执行101的task泳道 >> ,1,901,907,902,908,903,909,904,910,905,906,
测试更新执行102的story泳道 >> ,6,8,
测试更新执行102的bug泳道 >> ,5,302,4,6,
测试更新执行102的task泳道 >> ,2,604,605,606,
测试更新执行103的story泳道 >> ,10,12,
测试更新执行103的bug泳道 >> ,7,9,303,8,
测试更新执行103的task泳道 >> ,607,608,3,609,

*/

$executionID = array('101', '102', '103');
$laneType    = array('story', 'bug', 'task');

$kanban = new kanbanTest();

r($kanban->updateLaneTest($executionID[0], $laneType[0])) && p() && e('0');                                           // 测试更新执行101的story泳道
r($kanban->updateLaneTest($executionID[0], $laneType[1])) && p() && e(',1,3,301,2,');                                 // 测试更新执行101的bug泳道
r($kanban->updateLaneTest($executionID[0], $laneType[2])) && p() && e(',1,901,907,902,908,903,909,904,910,905,906,'); // 测试更新执行101的task泳道
r($kanban->updateLaneTest($executionID[1], $laneType[0])) && p() && e(',6,8,');                                       // 测试更新执行102的story泳道
r($kanban->updateLaneTest($executionID[1], $laneType[1])) && p() && e(',5,302,4,6,');                                 // 测试更新执行102的bug泳道
r($kanban->updateLaneTest($executionID[1], $laneType[2])) && p() && e(',2,604,605,606,');                             // 测试更新执行102的task泳道
r($kanban->updateLaneTest($executionID[2], $laneType[0])) && p() && e(',10,12,');                                     // 测试更新执行103的story泳道
r($kanban->updateLaneTest($executionID[2], $laneType[1])) && p() && e(',7,9,303,8,');                                 // 测试更新执行103的bug泳道
r($kanban->updateLaneTest($executionID[2], $laneType[2])) && p() && e(',607,608,3,609,');                             // 测试更新执行103的task泳道