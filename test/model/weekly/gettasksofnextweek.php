#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getTasksOfNextWeek();
cid=1
pid=1

测试project值为0，date值为2022-07-30 >> 子任务6
测试project值为0，date值为空 >> 0
测试project值为1，date值为2022-07-30 >> 0
测试project值为1，date值为空 >> 0
测试project值为11，date值为2022-07-30 >> 开发任务371
测试project值为11，date值为空 >> 0

*/

$projectList = array(0, 1, 11);
$dateList    = array('2022-07-30', '');

$weekly = new weeklyTest();
r($weekly->getTasksOfNextWeekTest($projectList[0], $dateList[0])) && p('906:name') && e('子任务6');     //测试project值为0，date值为2022-07-30
r($weekly->getTasksOfNextWeekTest($projectList[0], $dateList[1])) && p()           && e('0');          //测试project值为0，date值为空
r($weekly->getTasksOfNextWeekTest($projectList[1], $dateList[0])) && p()           && e('0');          //测试project值为1，date值为2022-07-30
r($weekly->getTasksOfNextWeekTest($projectList[1], $dateList[1])) && p()           && e('0');          //测试project值为1，date值为空
r($weekly->getTasksOfNextWeekTest($projectList[2], $dateList[0])) && p('361:name') && e('开发任务371'); //测试project值为11，date值为2022-07-30
r($weekly->getTasksOfNextWeekTest($projectList[2], $dateList[1])) && p()           && e('0');          //测试project值为11，date值为空