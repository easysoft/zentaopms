#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createExecutionLane();
cid=1
pid=1

创建执行101的泳道 >> 3
创建执行101 需求的泳道 >> 2
创建执行101 需求 按类别分组的泳道 >> 3
创建执行101的泳道 >> 3
创建执行102 需求 按来源分组的泳道 >> 3
创建执行102 任务 按优先级分组的泳道 >> 4
创建执行102 任务 按需求分组的泳道 >> 7
创建执行103的泳道 >> 3
创建执行103 bug  按严重程度分组的泳道 >> 4
创建执行103 bug  按模块分组的泳道 >> 8
创建执行103 bug  按指派给分组的泳道 >> 10
创建执行104的泳道 >> 3
创建执行105的泳道 >> 3

*/

$executionIDList = array('101', '102', '103', '104', '105');
$typeIDList      = array('all', 'story', 'task', 'bug');
$groupByIDList   = array('default', 'pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->createExecutionLaneTest($executionIDList[0]))                                    && p() && e('3');  // 创建执行101的泳道
r($kanban->createExecutionLaneTest($executionIDList[0], $typeIDList[1]))                    && p() && e('2');  // 创建执行101 需求的泳道
r($kanban->createExecutionLaneTest($executionIDList[0], $typeIDList[1], $groupByIDList[2])) && p() && e('3');  // 创建执行101 需求 按类别分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[1]))                                    && p() && e('3');  // 创建执行101的泳道
r($kanban->createExecutionLaneTest($executionIDList[1], $typeIDList[1], $groupByIDList[4])) && p() && e('3');  // 创建执行102 需求 按来源分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[1], $typeIDList[2], $groupByIDList[1])) && p() && e('4');  // 创建执行102 任务 按优先级分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[1], $typeIDList[2], $groupByIDList[6])) && p() && e('7');  // 创建执行102 任务 按需求分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[2]))                                    && p() && e('3');  // 创建执行103的泳道
r($kanban->createExecutionLaneTest($executionIDList[2], $typeIDList[3], $groupByIDList[7])) && p() && e('4');  // 创建执行103 bug  按严重程度分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[2], $typeIDList[3], $groupByIDList[3])) && p() && e('8');  // 创建执行103 bug  按模块分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[2], $typeIDList[3], $groupByIDList[5])) && p() && e('10'); // 创建执行103 bug  按指派给分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[3]))                                    && p() && e('3');  // 创建执行104的泳道
r($kanban->createExecutionLaneTest($executionIDList[4]))                                    && p() && e('3');  // 创建执行105的泳道
