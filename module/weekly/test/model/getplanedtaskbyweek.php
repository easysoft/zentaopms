#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 weeklyModel->getPlanedTaskByWeek();
cid=19726

- 测试project值为0，date值为2022-05-01第50条的name属性 @开发任务60
- 测试project值为0，date值为空第47条的name属性 @开发任务57
- 测试project值为1，date值为2022-05-01第42条的name属性 @开发任务52
- 测试project值为1，date值为空第31条的name属性 @开发任务41
- 测试project值为11，date值为2022-05-01第38条的name属性 @开发任务48
- 测试project值为11，date值为空第43条的name属性 @开发任务53

*/
$date = date('Y-m-d', time() + 7 * 24 * 3600);
$project = zenData('project');
$project->type->range('stage');
$project->project->range('0,1,11');
$project->gen(20);
$task = zenData('task');
$task->execution->range('1-20');
$task->deadline->range("`2022-04-01`,`2022-10-10`,`{$date}`,`0000-00-00`");
$task->gen(50);

$projectList = array(0, 1, 11);
$dateList    = array('2022-05-01', '');

$weekly = new weeklyModelTest();

r($weekly->getPlanedTaskByWeekTest($projectList[0], $dateList[0])) && p('50:name')  && e('开发任务60');  //测试project值为0，date值为2022-05-01
r($weekly->getPlanedTaskByWeekTest($projectList[0], $dateList[1])) && p('47:name')  && e('开发任务57');  //测试project值为0，date值为空
r($weekly->getPlanedTaskByWeekTest($projectList[1], $dateList[0])) && p('42:name')  && e('开发任务52');  //测试project值为1，date值为2022-05-01
r($weekly->getPlanedTaskByWeekTest($projectList[1], $dateList[1])) && p('31:name')  && e('开发任务41');  //测试project值为1，date值为空
r($weekly->getPlanedTaskByWeekTest($projectList[2], $dateList[0])) && p('38:name')  && e('开发任务48');  //测试project值为11，date值为2022-05-01
r($weekly->getPlanedTaskByWeekTest($projectList[2], $dateList[1])) && p('43:name')  && e('开发任务53');  //测试project值为11，date值为空
