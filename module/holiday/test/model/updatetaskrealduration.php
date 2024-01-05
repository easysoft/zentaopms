#!/usr/bin/env php
<?php
/**

title=测试 holidayModel->updateTaskRealDuration();
cid=1

- 测试插入id为 10 的节假日时任务 1 任务的计划工期 @1
- 测试插入id为 10 的节假日时任务 2 任务的计划工期 @1
- 测试插入id为 10 的节假日时任务 3 任务的计划工期 @1
- 测试插入id为 5 的节假日时任务 1 任务的计划工期 @1
- 测试插入id为 5 的节假日时任务 2 任务的计划工期 @1
- 测试插入id为 5 的节假日时任务 3 任务的计划工期 @1
- 测试插入id为 5 的节假日时任务 1 任务的计划工期 @1
- 测试插入id为 5 的节假日时任务 2 任务的计划工期 @1
- 测试插入id为 5 的节假日时任务 3 任务的计划工期 @1

*/
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->config('holiday')->gen(24);
zdTable('task')->config('task')->gen(10);
zdTable('user')->gen(1);

su('admin');

$holiday = new holidayTest();

$holidayIdList  = array(10, 5, 1);
$taskIdList     = array(1, 2, 3);
$updateDuration = array(true, false);

$holiday = new holidayTest();

r($holiday->updateTaskRealDurationTest($taskIdList[0], $holidayIdList[0], $updateDuration[0])) && p() && e('1'); //测试插入id为 10 的节假日时任务 1 任务的计划工期
r($holiday->updateTaskRealDurationTest($taskIdList[1], $holidayIdList[0], $updateDuration[1])) && p() && e('1'); //测试插入id为 10 的节假日时任务 2 任务的计划工期
r($holiday->updateTaskRealDurationTest($taskIdList[2], $holidayIdList[0], $updateDuration[1])) && p() && e('1'); //测试插入id为 10 的节假日时任务 3 任务的计划工期

r($holiday->updateTaskRealDurationTest($taskIdList[0], $holidayIdList[1], $updateDuration[0])) && p() && e('1'); //测试插入id为 5 的节假日时任务 1 任务的计划工期
r($holiday->updateTaskRealDurationTest($taskIdList[1], $holidayIdList[1], $updateDuration[1])) && p() && e('1'); //测试插入id为 5 的节假日时任务 2 任务的计划工期
r($holiday->updateTaskRealDurationTest($taskIdList[2], $holidayIdList[1], $updateDuration[1])) && p() && e('1'); //测试插入id为 5 的节假日时任务 3 任务的计划工期

r($holiday->updateTaskRealDurationTest($taskIdList[0], $holidayIdList[2], $updateDuration[0])) && p() && e('1'); //测试插入id为 5 的节假日时任务 1 任务的计划工期
r($holiday->updateTaskRealDurationTest($taskIdList[1], $holidayIdList[2], $updateDuration[1])) && p() && e('1'); //测试插入id为 5 的节假日时任务 2 任务的计划工期
r($holiday->updateTaskRealDurationTest($taskIdList[2], $holidayIdList[2], $updateDuration[1])) && p() && e('1'); //测试插入id为 5 的节假日时任务 3 任务的计划工期
