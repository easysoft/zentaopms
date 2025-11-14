#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';
su('admin');

zenData('kanbanlane')->gen(0);
zenData('project')->loadYaml('execution')->gen(110);

/**

title=测试 kanbanModel->createExecutionLane();
timeout=0
cid=16892

- 创建执行101的泳道 @4
- 创建执行101 需求的泳道 @2
- 创建执行101的泳道 @4
- 创建执行102 需求 按来源分组的泳道 @2
- 创建执行102 任务 按优先级分组的泳道 @2
- 创建执行103的泳道 @4
- 创建执行103 bug  按严重程度分组的泳道 @2
- 创建执行104的泳道 @4
- 创建执行105的泳道 @4

*/

$executionIDList = array('101', '102', '103', '104', '105');
$typeIDList      = array('all', 'story', 'task', 'bug');

global $config;
$kanban = new kanbanTest();
if(isset($config->kanban->default->risk)) unset($config->kanban->default->risk); // 删除风险泳道

r($kanban->createExecutionLaneTest($executionIDList[0]))                 && p() && e('4');  // 创建执行101的泳道
r($kanban->createExecutionLaneTest($executionIDList[0], $typeIDList[1])) && p() && e('2');  // 创建执行101 需求的泳道
r($kanban->createExecutionLaneTest($executionIDList[1]))                 && p() && e('4');  // 创建执行101的泳道
r($kanban->createExecutionLaneTest($executionIDList[1], $typeIDList[1])) && p() && e('2');  // 创建执行102 需求 按来源分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[1], $typeIDList[2])) && p() && e('2');  // 创建执行102 任务 按优先级分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[2]))                 && p() && e('4');  // 创建执行103的泳道
r($kanban->createExecutionLaneTest($executionIDList[2], $typeIDList[3])) && p() && e('2');  // 创建执行103 bug  按严重程度分组的泳道
r($kanban->createExecutionLaneTest($executionIDList[3]))                 && p() && e('4');  // 创建执行104的泳道
r($kanban->createExecutionLaneTest($executionIDList[4]))                 && p() && e('4');  // 创建执行105的泳道
