#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 weeklyModel->getPVEV();
cid=19728
pid=1

- 测试projectID值为0，date值为2022-05-01 @20.00,60.68
- 测试projectID值为0，date值为空属性1 @60.68
- 测试projectID值为11，date值为2022-05-01 @12.00,34.67
- 测试projectID值为11，date值为空属性1 @34.67
- 测试projectID值为41，date值为2022-05-01 @0.00,0.00
- 测试projectID值为41，date值为空 @0.00,0.00

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

$projectIDList = array(0, 11, 41);
$dateList      = array('2022-05-01', '');

$weekly = new weeklyModelTest();

r($weekly->getPVEVTest($projectIDList[0], $dateList[0]))               && p()  && e('20.00,60.68'); //测试projectID值为0，date值为2022-05-01
r(explode(',', $weekly->getPVEVTest($projectIDList[0], $dateList[1]))) && p(1) && e('60.68');       //测试projectID值为0，date值为空
r($weekly->getPVEVTest($projectIDList[1], $dateList[0]))               && p()  && e('12.00,34.67'); //测试projectID值为11，date值为2022-05-01
r(explode(',', $weekly->getPVEVTest($projectIDList[1], $dateList[1]))) && p(1) && e('34.67');       //测试projectID值为11，date值为空
r($weekly->getPVEVTest($projectIDList[2], $dateList[0]))               && p()  && e('0.00,0.00');   //测试projectID值为41，date值为2022-05-01
r($weekly->getPVEVTest($projectIDList[2], $dateList[1]))               && p()  && e('0.00,0.00');   //测试projectID值为41，date值为空
