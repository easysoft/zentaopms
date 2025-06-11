#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getTasksOfNextWeek();
cid=1

- 测试project值为0，date值为2022-07-30第48条的name属性 @开发任务58
- 测试project值为0，date值为空 @0
- 测试project值为1，date值为2022-07-30第48条的name属性 @开发任务58
- 测试project值为1，date值为空 @0
- 测试project值为11，date值为2022-07-30第32条的name属性 @开发任务42
- 测试project值为11，date值为空 @0

*/
$date = date('Y-m-d', time() + 5 * 24 * 3600);
$project = zenData('project');
$project->type->range('stage');
$project->project->range('0,1,11');
$project->gen(20);
$task = zenData('task');
$task->execution->range('1-20');
$task->deadline->range("`2022-07-25`,`2022-07-31`,`{$date}`,`2022-08-02`");
$task->gen(50);

$projectList = array(0, 1, 11);
$dateList    = array('2022-07-30', '');

$weekly = new weeklyTest();
r($weekly->getTasksOfNextWeekTest($projectList[0], $dateList[0])) && p('48:name') && e('开发任务58'); //测试project值为0，date值为2022-07-30
r($weekly->getTasksOfNextWeekTest($projectList[0], $dateList[1])) && p()          && e('0');          //测试project值为0，date值为空
r($weekly->getTasksOfNextWeekTest($projectList[1], $dateList[0])) && p('48:name') && e('开发任务58'); //测试project值为1，date值为2022-07-30
r($weekly->getTasksOfNextWeekTest($projectList[1], $dateList[1])) && p()          && e('0');          //测试project值为1，date值为空
r($weekly->getTasksOfNextWeekTest($projectList[2], $dateList[0])) && p('32:name') && e('开发任务42'); //测试project值为11，date值为2022-07-30
r($weekly->getTasksOfNextWeekTest($projectList[2], $dateList[1])) && p()          && e('0');          //测试project值为11，date值为空
