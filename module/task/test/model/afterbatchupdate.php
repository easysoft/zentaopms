#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('story')->gen(5);
zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(15);

/**

title=测试taskModel->afterBatchUpdate();
timeout=0
cid=18759

- 检查修改任务名称后的数据处理 @1
- 检查修改任务模块后的数据处理 @1
- 检查修改任务类型后的数据处理 @1
- 检查修改任务指派人后的数据处理 @1
- 检查修改任务状态后的数据处理 @1
- 检查修改任务预计开始后的数据处理 @1
- 检查修改任务截止日期后的数据处理 @1
- 检查修改任务优先级后的数据处理 @1
- 检查修改任务预计工时后的数据处理 @1
- 检查修改任务消耗工时后的数据处理 @1
- 检查修改任务剩余工时后的数据处理 @1
- 检查修改任务完成者后的数据处理 @1
- 检查修改任务关闭者后的数据处理 @1
- 检查修改子任务状态的数据处理 @1
- 检查修改子任务消耗工时的数据处理 @1

*/

$taskIdList = range(1, 15);

$changeName          = array('id' => 1, 'name' => '修改1');
$changeModule        = array('id' => 2, 'module' => 1);
$changeType          = array('id' => 3, 'type' => 'devel');
$changeAssignedTo    = array('id' => 4, 'assignedTo' => 'admin');
$changeStatus        = array('id' => 5, 'status' => 'done');
$changeEstStarted    = array('id' => 6, 'estStarted' => '2023-05-25');
$changeDeadline      = array('id' => 7, 'estStarted' => '2023-01-02', 'deadline' => '2023-05-25');
$changePri           = array('id' => 8, 'pri' => 1);
$changeEstimate      = array('id' => 9, 'estimate' => 1);
$changeConsumed      = array('id' => 10, 'status' => 'doing', 'consumed' => 1);
$changeLeft          = array('id' => 11, 'status' => 'doing', 'consumed' => 2, 'left' => 1);
$changeFinishedBy    = array('id' => 12, 'status' => 'done', 'finishedBy' => 'admin');
$changeClosedBy      = array('id' => 13, 'status' => 'closed', 'closedBy' => 'admin', 'closedReason' => 'closed');
$changeChildStatus   = array('id' => 14, 'status' => 'doing');
$changeChildConsumed = array('id' => 15, 'status' => 'doing', 'consumed' => 1);

$taskTester = new taskModelTest();
r($taskTester->afterBatchUpdateObject($taskIdList, $changeName))          && p() && e('1'); // 检查修改任务名称后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeModule))        && p() && e('1'); // 检查修改任务模块后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeType))          && p() && e('1'); // 检查修改任务类型后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeAssignedTo))    && p() && e('1'); // 检查修改任务指派人后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeStatus))        && p() && e('1'); // 检查修改任务状态后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeEstStarted))    && p() && e('1'); // 检查修改任务预计开始后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeDeadline))      && p() && e('1'); // 检查修改任务截止日期后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changePri))           && p() && e('1'); // 检查修改任务优先级后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeEstimate))      && p() && e('1'); // 检查修改任务预计工时后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeConsumed))      && p() && e('1'); // 检查修改任务消耗工时后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeLeft))          && p() && e('1'); // 检查修改任务剩余工时后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeFinishedBy))    && p() && e('1'); // 检查修改任务完成者后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeClosedBy))      && p() && e('1'); // 检查修改任务关闭者后的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeChildStatus))   && p() && e('1'); // 检查修改子任务状态的数据处理
r($taskTester->afterBatchUpdateObject($taskIdList, $changeChildConsumed)) && p() && e('1'); // 检查修改子任务消耗工时的数据处理