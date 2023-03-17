#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->updateProgramPlanDuration();
cid=1
pid=1

测试插入id为1的节假日时迭代101项目的planDuration >> 59
测试插入id为3的节假日时迭代101项目的planDuration >> 59
测试插入id为1的节假日时迭代102项目的planDuration >> 58
测试插入id为3的节假日时迭代102项目的planDuration >> 58
测试插入id为1的节假日时迭代750项目的planDuration >> 58
测试插入id为3的节假日时迭代750项目的planDuration >> 58
测试插入id为1的节假日时迭代1项目的planDuration >> 59
测试插入id为3的节假日时迭代1项目的planDuration >> 59
测试插入id为1的节假日时迭代11项目的planDuration >> 59
测试插入id为3的节假日时迭代11项目的planDuration >> 59

*/
$holidayIDList = array('1', '3', '99', '100');

$holiday = new holidayTest();

r($holiday->updateProgramPlanDurationTest(101, $holidayIDList[0])) && p() && e('59'); //测试插入id为1的节假日时迭代101项目的planDuration
r($holiday->updateProgramPlanDurationTest(101, $holidayIDList[1])) && p() && e('59'); //测试插入id为3的节假日时迭代101项目的planDuration
r($holiday->updateProgramPlanDurationTest(102, $holidayIDList[0])) && p() && e('58'); //测试插入id为1的节假日时迭代102项目的planDuration
r($holiday->updateProgramPlanDurationTest(102, $holidayIDList[1])) && p() && e('58'); //测试插入id为3的节假日时迭代102项目的planDuration
r($holiday->updateProgramPlanDurationTest(750, $holidayIDList[0])) && p() && e('58'); //测试插入id为1的节假日时迭代750项目的planDuration
r($holiday->updateProgramPlanDurationTest(750, $holidayIDList[1])) && p() && e('58'); //测试插入id为3的节假日时迭代750项目的planDuration
r($holiday->updateProgramPlanDurationTest(1,   $holidayIDList[0])) && p() && e('59'); //测试插入id为1的节假日时迭代1项目的planDuration
r($holiday->updateProgramPlanDurationTest(1,   $holidayIDList[1])) && p() && e('59'); //测试插入id为3的节假日时迭代1项目的planDuration
r($holiday->updateProgramPlanDurationTest(11,  $holidayIDList[0])) && p() && e('59'); //测试插入id为1的节假日时迭代11项目的planDuration
r($holiday->updateProgramPlanDurationTest(11,  $holidayIDList[1])) && p() && e('59'); //测试插入id为3的节假日时迭代11项目的planDuration

