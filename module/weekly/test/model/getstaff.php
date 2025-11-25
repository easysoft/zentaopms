#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getStaff();
cid=19730

- 测试project值为0，date值为2022-05-01 @0
- 测试project值为0，date值为空 @0
- 测试project值为1，date值为2022-05-01 @0
- 测试project值为1，date值为空 @0
- 测试project值为11，date值为2022-05-01 @0
- 测试project值为11，date值为空 @0

*/
$date = date('Y-m-d');
$project = zenData('project');
$project->type->range('stage');
$project->project->range('0,1,11');
$project->gen(20);
$task = zenData('task');
$task->execution->range('1-20');
$task->deadline->range("`2022-04-25`,`2022-05-31`,`{$date}`,`0000-00-00`");
$task->gen(50);

$projectList = array(0, 1, 11);
$dateList    = array('2022-05-01', '');

$weekly = new weeklyTest();

r($weekly->getStaffTest($projectList[0], $dateList[0])) && p() && e('0'); //测试project值为0，date值为2022-05-01
r($weekly->getStaffTest($projectList[0], $dateList[1])) && p() && e('0'); //测试project值为0，date值为空
r($weekly->getStaffTest($projectList[1], $dateList[0])) && p() && e('0'); //测试project值为1，date值为2022-05-01
r($weekly->getStaffTest($projectList[1], $dateList[1])) && p() && e('0'); //测试project值为1，date值为空
r($weekly->getStaffTest($projectList[2], $dateList[0])) && p() && e('0'); //测试project值为11，date值为2022-05-01
r($weekly->getStaffTest($projectList[2], $dateList[1])) && p() && e('0'); //测试project值为11，date值为空
