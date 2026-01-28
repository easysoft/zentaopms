#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getGanttTasks();
timeout=0
cid=17744

- 测试查询项目 11 执行 101 browseType 空 queryID 0 的甘特图任务 @1
- 测试查询项目 11 执行 101 browseType 空 queryID 1 的甘特图任务 @1
- 测试查询项目 11 执行 101 browseType bysearch queryID 0 的甘特图任务 @1
- 测试查询项目 11 执行 101 browseType bysearch queryID 1 的甘特图任务 @0
- 测试查询项目 12 执行 102 browseType 空 queryID 0 的甘特图任务 @2
- 测试查询项目 12 执行 102 browseType 空 queryID 1 的甘特图任务 @2
- 测试查询项目 12 执行 102 browseType bysearch queryID 0 的甘特图任务 @0
- 测试查询项目 12 执行 102 browseType bysearch queryID 1 的甘特图任务 @0
- 测试查询不存在的项目 101 执行 101 browseType 空 queryID 0 的甘特图任务 @0
- 测试查询项目 11 执行 101 browseType 空 queryID 0 的甘特图任务
 - 第1条的storyVersion属性 @1
 - 第1条的storyStatus属性 @active
 - 第1条的needConfirm属性 @~~
- 测试查询项目 11 执行 101 browseType 空 queryID 1 的甘特图任务
 - 第1条的storyVersion属性 @1
 - 第1条的storyStatus属性 @active
 - 第1条的needConfirm属性 @~~
- 测试查询项目 11 执行 101 browseType bysearch queryID 0 的甘特图任务 @0
- 测试查询项目 11 执行 101 browseType bysearch queryID 1 的甘特图任务 @0
- 测试查询项目 12 执行 102 browseType 空 queryID 0 的甘特图任务
 - 第2条的storyVersion属性 @1
 - 第2条的storyStatus属性 @active
 - 第2条的needConfirm属性 @~~
- 测试查询项目 12 执行 102 browseType 空 queryID 1 的甘特图任务
 - 第2条的storyVersion属性 @1
 - 第2条的storyStatus属性 @active
 - 第2条的needConfirm属性 @~~
- 测试查询项目 12 执行 102 browseType bysearch queryID 0 的甘特图任务 @0
- 测试查询项目 12 执行 102 browseType bysearch queryID 1 的甘特图任务 @0
- 测试查询不存在的项目 101 执行 101 browseType 空 queryID 0 的甘特图任务 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$task = zenData('task');
$task->story->range('1-30');
$task->storyVersion->range('1{6},2{4}');
$task->gen(30);

$story = zenData('story');
$story->id->range('1-30');
$story->status->range('active');
$story->version->range('1{6},2{2},3{2}');
$story->gen(30);

zenData('userquery')->gen(1);

$projectID  = array(11, 12, 101);
$planIdList = array(array(101), array(102));
$browseType = array('', 'bysearch');
$queryID    = array(0, 1);

$programplan = new programplanModelTest();
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[0], $queryID[0])) && p() && e('1'); // 测试查询项目 11 执行 101 browseType 空 queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[0], $queryID[1])) && p() && e('1'); // 测试查询项目 11 执行 101 browseType 空 queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[1], $queryID[0])) && p() && e('1'); // 测试查询项目 11 执行 101 browseType bysearch queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[1], $queryID[1])) && p() && e('0'); // 测试查询项目 11 执行 101 browseType bysearch queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[0], $queryID[0])) && p() && e('2'); // 测试查询项目 12 执行 102 browseType 空 queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[0], $queryID[1])) && p() && e('2'); // 测试查询项目 12 执行 102 browseType 空 queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[1], $queryID[0])) && p() && e('0'); // 测试查询项目 12 执行 102 browseType bysearch queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[1], $queryID[1])) && p() && e('0'); // 测试查询项目 12 执行 102 browseType bysearch queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[2], $planIdList[0], $browseType[0], $queryID[0])) && p() && e('0'); // 测试查询不存在的项目 101 执行 101 browseType 空 queryID 0 的甘特图任务

r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[0], $queryID[0], false)) && p('1:storyVersion,storyStatus,needConfirm') && e('1,active,~~'); // 测试查询项目 11 执行 101 browseType 空 queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[0], $queryID[1], false)) && p('1:storyVersion,storyStatus,needConfirm') && e('1,active,~~'); // 测试查询项目 11 执行 101 browseType 空 queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[1], $queryID[0], false)) && p()                                         && e('0');           // 测试查询项目 11 执行 101 browseType bysearch queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[0], $planIdList[0], $browseType[1], $queryID[1], false)) && p()                                         && e('0');           // 测试查询项目 11 执行 101 browseType bysearch queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[0], $queryID[0], false)) && p('2:storyVersion,storyStatus,needConfirm') && e('1,active,~~'); // 测试查询项目 12 执行 102 browseType 空 queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[0], $queryID[1], false)) && p('2:storyVersion,storyStatus,needConfirm') && e('1,active,~~'); // 测试查询项目 12 执行 102 browseType 空 queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[1], $queryID[0], false)) && p()                                         && e('0');           // 测试查询项目 12 执行 102 browseType bysearch queryID 0 的甘特图任务
r($programplan->getGanttTasksTest($projectID[1], $planIdList[1], $browseType[1], $queryID[1], false)) && p()                                         && e('0');           // 测试查询项目 12 执行 102 browseType bysearch queryID 1 的甘特图任务
r($programplan->getGanttTasksTest($projectID[2], $planIdList[0], $browseType[0], $queryID[0], false)) && p()                                         && e('0');           // 测试查询不存在的项目 101 执行 101 browseType 空 queryID 0 的甘特图任务