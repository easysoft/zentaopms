#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancell')->gen(0);

/**

title=测试 kanbanModel->createExecutionColumns();
timeout=0
cid=1

- 创建泳道 100001 执行101 需求的看板列 @11
- 创建泳道 100001 执行101 任务的看板列 @7
- 创建泳道 100001 执行101 bug 的看板列 @9
- 创建泳道 100002 执行102 需求的看板列 @11
- 创建泳道 100002 执行102 任务的看板列 @7
- 创建泳道 100002 执行102 bug 的看板列 @14
- 创建泳道 100003 执行103 需求的看板列 @11
- 创建泳道 100003 执行103 任务的看板列 @7
- 创建泳道 100003 执行103 bug 的看板列 @9
- 创建泳道 100004 执行104 需求的看板列 @11
- 创建泳道 100004 执行104 任务的看板列 @7
- 创建泳道 100004 执行104 bug 的看板列 @9
- 创建泳道 100005 执行105 需求的看板列 @11
- 创建泳道 100005 执行105 任务的看板列 @7
- 创建泳道 100005 执行105 bug 的看板列 @9

*/

$laneIDList      = array('100001', '100002', '100003', '10004', '10005');
$executionIDList = array('101', '102', '103', '104', '105');
$typeList        = array('story', 'task', 'bug');

$kanban = new kanbanTest();

r($kanban->createExecutionColumnsTest($laneIDList[0], $typeList[0], $executionIDList[0])) && p() && e('11'); // 创建泳道 100001 执行101 需求的看板列
r($kanban->createExecutionColumnsTest($laneIDList[0], $typeList[1], $executionIDList[0])) && p() && e('7');  // 创建泳道 100001 执行101 任务的看板列
r($kanban->createExecutionColumnsTest($laneIDList[0], $typeList[2], $executionIDList[0])) && p() && e('9');  // 创建泳道 100001 执行101 bug 的看板列
r($kanban->createExecutionColumnsTest($laneIDList[1], $typeList[0], $executionIDList[1])) && p() && e('11'); // 创建泳道 100002 执行102 需求的看板列
r($kanban->createExecutionColumnsTest($laneIDList[1], $typeList[1], $executionIDList[1])) && p() && e('7');  // 创建泳道 100002 执行102 任务的看板列
r($kanban->createExecutionColumnsTest($laneIDList[1], $typeList[1], $executionIDList[1])) && p() && e('14'); // 创建泳道 100002 执行102 bug 的看板列
r($kanban->createExecutionColumnsTest($laneIDList[2], $typeList[0], $executionIDList[2])) && p() && e('11'); // 创建泳道 100003 执行103 需求的看板列
r($kanban->createExecutionColumnsTest($laneIDList[2], $typeList[1], $executionIDList[2])) && p() && e('7');  // 创建泳道 100003 执行103 任务的看板列
r($kanban->createExecutionColumnsTest($laneIDList[2], $typeList[2], $executionIDList[2])) && p() && e('9');  // 创建泳道 100003 执行103 bug 的看板列
r($kanban->createExecutionColumnsTest($laneIDList[3], $typeList[0], $executionIDList[3])) && p() && e('11'); // 创建泳道 100004 执行104 需求的看板列
r($kanban->createExecutionColumnsTest($laneIDList[3], $typeList[1], $executionIDList[3])) && p() && e('7');  // 创建泳道 100004 执行104 任务的看板列
r($kanban->createExecutionColumnsTest($laneIDList[3], $typeList[2], $executionIDList[3])) && p() && e('9');  // 创建泳道 100004 执行104 bug 的看板列
r($kanban->createExecutionColumnsTest($laneIDList[4], $typeList[0], $executionIDList[4])) && p() && e('11'); // 创建泳道 100005 执行105 需求的看板列
r($kanban->createExecutionColumnsTest($laneIDList[4], $typeList[1], $executionIDList[4])) && p() && e('7');  // 创建泳道 100005 执行105 任务的看板列
r($kanban->createExecutionColumnsTest($laneIDList[4], $typeList[2], $executionIDList[4])) && p() && e('9');  // 创建泳道 100005 执行105 bug 的看板列