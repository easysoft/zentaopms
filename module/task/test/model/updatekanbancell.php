#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('task')->loadYaml('task_updatekanbancell')->gen(7);
zenData('project')->loadYaml('project_updatekanbancell')->gen(1);

zenData('kanbanregion')->loadYaml('kanbanregion_updatekanbancell')->gen(1);
zenData('kanbanlane')->loadYaml('kanbanlane_updatekanbancell')->gen(1);
zenData('kanbancolumn')->loadYaml('kanbancolumn_updatekanbancell')->gen(7);
zenData('kanbancell')->loadYaml('kanbancell_updatekanbancell')->gen(7);

/**

title=taskModel->updateKanbanCell();
timeout=0
cid=18852

- 测试获取wait状态任务更新看板单元格不传output后的看板单元格数据 @1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,

- 测试获取doing状态任务更新看板单元格不传output后的看板单元格数据 @1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,

- 测试获取done状态任务更新看板单元格不传output后的看板单元格数据 @1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,

- 测试获取cancel状态任务更新看板单元格不传output后的看板单元格数据 @1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,

- 测试获取closed状态任务更新看板单元格不传output后的看板单元格数据 @1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,

- 测试获取wait状态从第一列挪到第二列后的看板单元格数据 @1:|2:,1,|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,

- 测试获取wait状态从第二列挪到第六列后的看板单元格数据 @1:|2:|3:,2,6,7,|4:,3,|5:|6:,4,1,|7:,5,

*/

$taskIDList  = array(1, 2, 3, 4, 5, 7);
$executionID = 1;

$output   = array();
$output[] = array('fromColID' => 1, 'toColID' => 2, 'fromLaneID' => 1, 'toLaneID' => 1);
$output[] = array('fromColID' => 2, 'toColID' => 6, 'fromLaneID' => 1, 'toLaneID' => 1);

$task = new taskModelTest();
r($task->updateKanbanCellTest($taskIDList[0], $executionID, array()))    && p() && e('1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,'); // 测试获取wait状态任务更新看板单元格不传output后的看板单元格数据
r($task->updateKanbanCellTest($taskIDList[1], $executionID, array()))    && p() && e('1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,'); // 测试获取doing状态任务更新看板单元格不传output后的看板单元格数据
r($task->updateKanbanCellTest($taskIDList[2], $executionID, array()))    && p() && e('1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,'); // 测试获取done状态任务更新看板单元格不传output后的看板单元格数据
r($task->updateKanbanCellTest($taskIDList[3], $executionID, array()))    && p() && e('1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,'); // 测试获取cancel状态任务更新看板单元格不传output后的看板单元格数据
r($task->updateKanbanCellTest($taskIDList[4], $executionID, array()))    && p() && e('1:,1,|2:|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,'); // 测试获取closed状态任务更新看板单元格不传output后的看板单元格数据
r($task->updateKanbanCellTest($taskIDList[0], $executionID, $output[0])) && p() && e('1:|2:,1,|3:,2,6,7,|4:,3,|5:|6:,4,|7:,5,'); // 测试获取wait状态从第一列挪到第二列后的看板单元格数据
r($task->updateKanbanCellTest($taskIDList[0], $executionID, $output[1])) && p() && e('1:|2:|3:,2,6,7,|4:,3,|5:|6:,4,1,|7:,5,');  // 测试获取wait状态从第二列挪到第六列后的看板单元格数据