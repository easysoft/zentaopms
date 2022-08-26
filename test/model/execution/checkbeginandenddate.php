#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试 executionModel->checkBeginAndEndDate();
cid=1
pid=1

测试传入空值 >> 1
测试检查正常的日期 >> 1
测试检查小于开始的日期 >> 迭代开始日期应大于等于项目的开始日期：2022-05-26。\n
测试检查大于开始的日期 >> 迭代截止日期应小于等于项目的截止日期：2022-09-30。\n
测试检查等于开始跟结束的日期 >> 1

*/

$projectIdList = array(0, 11);
$checkType     = array('empty', 'normal', 'lt', 'gt', 'eq');

$execution = new executionTest();

r($execution->checkBeginAndEndDateTest($projectIdList[0], $checkType[0])) && p() && e('1');                                                    // 测试传入空值
r($execution->checkBeginAndEndDateTest($projectIdList[1], $checkType[1])) && p() && e('1');                                                    // 测试检查正常的日期
r($execution->checkBeginAndEndDateTest($projectIdList[1], $checkType[2])) && p() && e('迭代开始日期应大于等于项目的开始日期：2022-05-26。\n'); // 测试检查小于开始的日期
r($execution->checkBeginAndEndDateTest($projectIdList[1], $checkType[3])) && p() && e('迭代截止日期应小于等于项目的截止日期：2022-09-30。\n'); // 测试检查大于开始的日期
r($execution->checkBeginAndEndDateTest($projectIdList[1], $checkType[4])) && p() && e('1');                                                    // 测试检查等于开始跟结束的日期
