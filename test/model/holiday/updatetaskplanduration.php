#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->updateTaskPlanDurationTest();
cid=1
pid=1

测试插入id为1的holiday时任务1的planDuration >> 6
测试插入id为3的holiday时任务1的planDuration >> 6
测试插入id为1的holiday时任务2的planDuration >> 6
测试插入id为3的holiday时任务2的planDuration >> 6
测试插入id为1的holiday时任务11的planDuration >> 0
测试插入id为3的holiday时任务11的planDuration >> 0

*/
$holidayIDList = array('1', '3', '99', '100');

$holiday = new holidayTest();

r($holiday->updateTaskPlanDurationTest(1,  $holidayIDList[0])) && p() && e('6'); //测试插入id为1的holiday时任务1的planDuration
r($holiday->updateTaskPlanDurationTest(1,  $holidayIDList[1])) && p() && e('6'); //测试插入id为3的holiday时任务1的planDuration
r($holiday->updateTaskPlanDurationTest(2,  $holidayIDList[0])) && p() && e('6'); //测试插入id为1的holiday时任务2的planDuration
r($holiday->updateTaskPlanDurationTest(2,  $holidayIDList[1])) && p() && e('6'); //测试插入id为3的holiday时任务2的planDuration
r($holiday->updateTaskPlanDurationTest(11, $holidayIDList[0])) && p() && e('0'); //测试插入id为1的holiday时任务11的planDuration
r($holiday->updateTaskPlanDurationTest(11, $holidayIDList[1])) && p() && e('0'); //测试插入id为3的holiday时任务11的planDuration