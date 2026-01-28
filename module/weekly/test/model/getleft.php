#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 weeklyModel::getLeft();
timeout=0
cid=19724

- 测试项目ID为0,日期为2022-05-01 @102.00
- 测试项目ID为0,日期为空 @102.00
- 测试项目ID为11,日期为2022-05-01 @84.00
- 测试项目ID为11,日期为空 @84.00
- 测试项目ID不存在,日期为2022-05-01 @0.00
- 测试项目ID不存在,日期为空 @0.00

*/

$date = date('Y-m-d');

$project = zenData('project');
$project->type->range('stage');
$project->project->range('0,1,11');
$project->vision->range('rnd');
$project->gen(20);

$task = zenData('task');
$task->execution->range('1-20');
$task->deadline->range("`2022-04-25`,`2022-05-31`,`{$date}`,`0000-00-00`");
$task->estStarted->range("`2022-04-01`,`2022-04-10`,`2022-04-20`");
$task->left->range('1-10');
$task->status->range('wait,doing,done,pause');
$task->isParent->range('0');
$task->gen(50);

$projectIDList = array(0, 11, 41);
$dateList      = array('2022-05-01', '');

$weekly = new weeklyModelTest();

r($weekly->getLeftTest($projectIDList[0], $dateList[0])) && p() && e('102.00'); // 测试项目ID为0,日期为2022-05-01
r($weekly->getLeftTest($projectIDList[0], $dateList[1])) && p() && e('102.00'); // 测试项目ID为0,日期为空
r($weekly->getLeftTest($projectIDList[1], $dateList[0])) && p() && e('84.00'); // 测试项目ID为11,日期为2022-05-01
r($weekly->getLeftTest($projectIDList[1], $dateList[1])) && p() && e('84.00'); // 测试项目ID为11,日期为空
r($weekly->getLeftTest($projectIDList[2], $dateList[0])) && p() && e('0.00');   // 测试项目ID不存在,日期为2022-05-01
r($weekly->getLeftTest($projectIDList[2], $dateList[1])) && p() && e('0.00');   // 测试项目ID不存在,日期为空