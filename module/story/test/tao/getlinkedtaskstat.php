#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getLinkedTaskStat();
cid=18639

- 不传入任何数据。 @0
- 只传入需求 ID。 @0
- 只传入关联的执行。 @0
- 传入需求 ID, 只关联分支 0，检查是否有未关联的分支出现。 @1
- 传入需求 ID, 只关联分支 0，检查开发任务未开始的任务数。 @8
- 传入需求 ID, 只关联分支 0，检查开发任务数和统计是否一致。 @1
- 传入需求 ID, 只关联分支 0，检查测试任务数和统计是否一致。 @1
- 传入需求 ID, 只关联分支 1，检查是否有未关联的分支出现。 @1
- 传入需求 ID, 只关联分支 1，检查开发任务未开始的任务数。 @8
- 传入需求 ID, 只关联分支 1，检查开发任务数和统计是否一致。 @1
- 传入需求 ID, 只关联分支 1，检查测试任务数和统计是否一致。 @1
- 传入需求 ID, 关联分支 0,1,2，检查开发任务未开始的任务数。 @8
- 传入需求 ID, 关联分支 0,1,2，检查开发任务数和统计是否一致。 @1
- 传入需求 ID, 关联分支 0,1,2，检查测试任务数和统计是否一致。 @1
- 传入需求 ID, 传入未关联的项目，检查是否有数据。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

$task = zenData('task');
$task->story->range('1');
$task->execution->range('1{2},2{2},3{2}');
$task->type->range('devel{6},test{6}');
$task->deleted->range('0{24},1');
$task->gen(100);

global $tester;
$storyModel = $tester->loadModel('story');

$linkedProjects = array();
$linkedProjects[1] = new stdclass();
$linkedProjects[1]->branches = array(0);

r($storyModel->getLinkedTaskStat(0, array()))         && p() && e('0'); //不传入任何数据。
r($storyModel->getLinkedTaskStat(1, array()))         && p() && e('0'); //只传入需求 ID。
r($storyModel->getLinkedTaskStat(0, $linkedProjects)) && p() && e('0'); //只传入关联的执行。

$linkedProjects = array();
$linkedProjects[1] = new stdclass();
$linkedProjects[1]->branches = array(0);
list($branchStatusList, $branchDevelCount, $branchTestCount) = $storyModel->getLinkedTaskStat(1, $linkedProjects);
r(!isset($branchStatusList[1])) && p() && e('1');                                     //传入需求 ID, 只关联分支 0，检查是否有未关联的分支出现。
r($branchStatusList[0]['devel']['wait']) && p() && e('8');                            //传入需求 ID, 只关联分支 0，检查开发任务未开始的任务数。
r(array_sum($branchStatusList[0]['devel']) == $branchDevelCount[0]) && p() && e('1'); //传入需求 ID, 只关联分支 0，检查开发任务数和统计是否一致。
r(array_sum($branchStatusList[0]['test'])  == $branchTestCount[0])  && p() && e('1'); //传入需求 ID, 只关联分支 0，检查测试任务数和统计是否一致。

$linkedProjects = array();
$linkedProjects[2] = new stdclass();
$linkedProjects[2]->branches = array(1);
list($branchStatusList, $branchDevelCount, $branchTestCount) = $storyModel->getLinkedTaskStat(1, $linkedProjects);
r(!isset($branchStatusList[0])) && p() && e('1');                                     //传入需求 ID, 只关联分支 1，检查是否有未关联的分支出现。
r($branchStatusList[1]['devel']['done']) && p() && e('8');                            //传入需求 ID, 只关联分支 1，检查开发任务未开始的任务数。
r(array_sum($branchStatusList[1]['devel']) == $branchDevelCount[1]) && p() && e('1'); //传入需求 ID, 只关联分支 1，检查开发任务数和统计是否一致。
r(array_sum($branchStatusList[1]['test'])  == $branchTestCount[1])  && p() && e('1'); //传入需求 ID, 只关联分支 1，检查测试任务数和统计是否一致。

$linkedProjects = array();
$linkedProjects[1] = new stdclass();
$linkedProjects[1]->branches = array(0);
$linkedProjects[2] = new stdclass();
$linkedProjects[2]->branches = array(1);
$linkedProjects[3] = new stdclass();
$linkedProjects[3]->branches = array(0, 2);
list($branchStatusList, $branchDevelCount, $branchTestCount) = $storyModel->getLinkedTaskStat(1, $linkedProjects);
r($branchStatusList[2]['devel']['done']) && p() && e('8');                            //传入需求 ID, 关联分支 0,1,2，检查开发任务未开始的任务数。
r(array_sum($branchStatusList[2]['devel']) == $branchDevelCount[2]) && p() && e('1'); //传入需求 ID, 关联分支 0,1,2，检查开发任务数和统计是否一致。
r(array_sum($branchStatusList[2]['test'])  == $branchTestCount[2])  && p() && e('1'); //传入需求 ID, 关联分支 0,1,2，检查测试任务数和统计是否一致。

$linkedProjects = array();
$linkedProjects[5] = new stdclass();
$linkedProjects[5]->branches = array(0);
r($storyModel->getLinkedTaskStat(1, $linkedProjects)) && p() && e('0'); //传入需求 ID, 传入未关联的项目，检查是否有数据。
