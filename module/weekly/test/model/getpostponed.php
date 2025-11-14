#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getPostponed();
cid=19727

- 测试project值为0，date值为2022-07-30第13条的name属性 @开发任务60
- 测试project值为0，date值为空第3条的name属性 @开发任务53
- 测试project值为1，date值为2022-07-30第5条的name属性 @开发任务47
- 测试project值为1，date值为空第0条的name属性 @开发任务41
- 测试project值为11，date值为2022-07-30第3条的name属性 @开发任务59
- 测试project值为11，date值为空第0条的name属性 @开发任务53

*/
$date = date('Y-m-d');
$project = zenData('project');
$project->type->range('stage');
$project->project->range('0,1,11');
$project->gen(20);
$task = zenData('task');
$task->execution->range('1-20');
$task->deadline->range("`2022-07-25`,`2022-07-31`,`{$date}`,`2023-05-10`");
$task->gen(50);

$projectList = array(0, 1, 11);
$dateList    = array('2022-07-30', '');

$weekly = new weeklyTest();

r($weekly->getPostponedTest($projectList[0], $dateList[0])) && p('13:name') && e('开发任务60');  //测试project值为0，date值为2022-07-30
r($weekly->getPostponedTest($projectList[0], $dateList[1])) && p('3:name')  && e('开发任务53');  //测试project值为0，date值为空
r($weekly->getPostponedTest($projectList[1], $dateList[0])) && p('5:name')  && e('开发任务47');  //测试project值为1，date值为2022-07-30
r($weekly->getPostponedTest($projectList[1], $dateList[1])) && p('0:name')  && e('开发任务41');  //测试project值为1，date值为空
r($weekly->getPostponedTest($projectList[2], $dateList[0])) && p('3:name')  && e('开发任务59');  //测试project值为11，date值为2022-07-30
r($weekly->getPostponedTest($projectList[2], $dateList[1])) && p('0:name')  && e('开发任务53');  //测试project值为11，date值为空
