#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project')->gen('10');

$story = zdTable('story');
$story->id->range('1');
$story->product->range('1');
$story->title->range('需求1');
$story->type->range('story');
$story->version->range('1');
$story->status->range('active');
$story->stage->range('projected');
$story->reviewedDate->range('(-1M)-(+1w):60m')->type('timestamp')->format('YY/MM/DD');
$story->lastEditedDate->range('(-1M)-(+1w):60m')->type('timestamp')->format('YY/MM/DD');
$story->gen('1');

$storyspec = zdTable('storyspec');
$storyspec->story->range('1');
$storyspec->version->range('1');
$storyspec->title->range('需求1');
$storyspec->spec->range('storyspec1');
$storyspec->gen('1');

$task = zdTable('task');
$task->id->range('1');
$task->name->range('任务1');
$task->execution->range('2');
$task->type->range('devel');
$task->status->range('wait');
$task->parent->range("`-1`");
$task->gen('1');

$taskspec = zdTable('taskspec');
$taskspec->task->range('1');
$taskspec->version->range('1');
$taskspec->name->range('任务一');
$taskspec->gen('1');

/**

title=测试taskTao->checkRequired4BatchCreate();
cid=1
pid=1


*/

$executionIdList = array(2, 3, 4, 5, 6, 7, 8 ,9 ,10);

$laneIdList   = array(1, 2, 3);
$columnIdList = array(1, 2, 3);

$parent        = array(0, 0, 0, 0, 1);
$module        = array(1, 2, 3, 4, 5);
$story         = array(1, 0);
$storyEstimate = array(1, 2, 1, 2, 1);
$storyDesc     = array('storyDesc1', 'storyDesc2', 'storyDesc3', '', '');
$storyPri      = array(3, 3, 1, 2, 4);
$name          = array('task1', 'task2', 'task3', 'task4', '');
$color         = array('', 'red', 'green', '', 'gray');
$type          = array('devel', 'request', 'design', 'test', 'study', 'discuss', 'ui', 'affair', 'misc');
$assignedTo    = array('admin', 'admin', 'dev1', 'dev2', 'admin');
$estimate      = array('1', '2', '3', '1', '1', 's', '-1');
$estStarted    = array('2022-07-01', '2023-05-08', '', '2023-09-09', '');
$deadline      = array('2023-07-01', '2022-01-02', '2023-01-01', '', '');
$desc          = array('', 'taskDesc', 'taskAAA', '', '');
$pri           = array('1', '2', '3', '3', '1');

$withoutStoryTask = array();
$withoutStoryTask[1] = new stdclass();
$withoutStoryTask[1]->execution  = $executionIdList[0];
$withoutStoryTask[1]->parent     = $parent[0];
$withoutStoryTask[1]->module     = $module[0];
$withoutStoryTask[1]->name       = $name[0];
$withoutStoryTask[1]->type       = $type[0];
$withoutStoryTask[1]->deadline   = $deadline[0];
$withoutStoryTask[1]->estimate   = $estimate[0];
$withoutStoryTask[1]->estStarted = $estStarted[0];

$requestStageTask = array();
$requestStageTask[1] = new stdclass();
$requestStageTask[1]->execution  = $executionIdList[0];
$requestStageTask[1]->parent     = $parent[0];
$requestStageTask[1]->module     = $module[0];
$requestStageTask[1]->name       = $name[1];
$requestStageTask[1]->type       = $type[0];
$requestStageTask[1]->deadline   = $deadline[0];
$requestStageTask[1]->estimate   = $estimate[0];
$requestStageTask[1]->estStarted = $estStarted[0];

$reviewStageTask = array();
$reviewStageTask[1] = new stdclass();
$reviewStageTask[1]->execution  = $executionIdList[0];
$reviewStageTask[1]->parent     = $parent[0];
$reviewStageTask[1]->module     = $module[0];
$reviewStageTask[1]->name       = $name[2];
$reviewStageTask[1]->type       = $type[0];
$reviewStageTask[1]->deadline   = $deadline[0];
$reviewStageTask[1]->estimate   = $estimate[0];
$reviewStageTask[1]->estStarted = $estStarted[0];

$devStageTask = array();
$devStageTask[1] = new stdclass();
$devStageTask[1]->execution  = $executionIdList[0];
$devStageTask[1]->parent     = $parent[0];
$devStageTask[1]->module     = $module[0];
$devStageTask[1]->name       = $name[3];
$devStageTask[1]->type       = $type[0];
$devStageTask[1]->deadline   = $deadline[0];
$devStageTask[1]->estimate   = $estimate[0];
$devStageTask[1]->estStarted = $estStarted[0];

$conformLimitedTask = array();
$conformLimitedTask[1] = new stdclass();
$conformLimitedTask[1]->execution  = $executionIdList[0];
$conformLimitedTask[1]->parent     = $parent[0];
$conformLimitedTask[1]->module     = $module[0];
$conformLimitedTask[1]->story      = $story[0];
$conformLimitedTask[1]->name       = $name[0];
$conformLimitedTask[1]->version    = 1;
$conformLimitedTask[1]->type       = $type[0];
$conformLimitedTask[1]->deadline   = $deadline[0];
$conformLimitedTask[1]->estimate   = $estimate[0];
$conformLimitedTask[1]->estStarted = $estStarted[0];
$conformLimitedTask[1]->mailto     = '';

$unConformLimitedTask = array();
$unConformLimitedTask[1] = new stdclass();
$unConformLimitedTask[1]->execution  = $executionIdList[0];
$unConformLimitedTask[1]->parent     = $parent[0];
$unConformLimitedTask[1]->module     = $module[0];
$unConformLimitedTask[1]->story      = $story[0];
$unConformLimitedTask[1]->name       = $name[0];
$unConformLimitedTask[1]->version    = 1;
$unConformLimitedTask[1]->type       = $type[0];
$unConformLimitedTask[1]->deadline   = $deadline[2];
$unConformLimitedTask[1]->estimate   = $estimate[0];
$unConformLimitedTask[1]->estStarted = $estStarted[0];
$unConformLimitedTask[1]->mailto     = '';

$deadlineSmallTask = array();
$deadlineSmallTask[1] = new stdclass();
$deadlineSmallTask[1]->execution  = $executionIdList[0];
$deadlineSmallTask[1]->parent     = $parent[0];
$deadlineSmallTask[1]->module     = $module[0];
$deadlineSmallTask[1]->story      = $story[0];
$deadlineSmallTask[1]->name       = $name[0];
$deadlineSmallTask[1]->version    = 1;
$deadlineSmallTask[1]->type       = $type[0];
$deadlineSmallTask[1]->estStarted = $estStarted[1];
$deadlineSmallTask[1]->deadline   = $deadline[1];
$deadlineSmallTask[1]->estimate   = $estimate[0];
$deadlineSmallTask[1]->mailto     = '';

$estimateNumberTask = array();
$estimateNumberTask[1] = new stdclass();
$estimateNumberTask[1]->execution  = $executionIdList[0];
$estimateNumberTask[1]->parent     = $parent[0];
$estimateNumberTask[1]->module     = $module[0];
$estimateNumberTask[1]->story      = $story[0];
$estimateNumberTask[1]->name       = $name[0];
$estimateNumberTask[1]->version    = 1;
$estimateNumberTask[1]->type       = $type[0];
$estimateNumberTask[1]->estStarted = $estStarted[0];
$estimateNumberTask[1]->deadline   = $deadline[0];
$estimateNumberTask[1]->estimate   = $estimate[5];
$estimateNumberTask[1]->mailto     = '';

$negativeNumberTask = array();
$negativeNumberTask[1] = new stdclass();
$negativeNumberTask[1]->execution  = $executionIdList[0];
$negativeNumberTask[1]->parent     = $parent[0];
$negativeNumberTask[1]->module     = $module[0];
$negativeNumberTask[1]->story      = $story[0];
$negativeNumberTask[1]->name       = $name[0];
$negativeNumberTask[1]->version    = 1;
$negativeNumberTask[1]->type       = $type[0];
$negativeNumberTask[1]->estStarted = $estStarted[0];
$negativeNumberTask[1]->deadline   = $deadline[0];
$negativeNumberTask[1]->estimate   = $estimate[6];
$negativeNumberTask[1]->mailto     = '';

$task = new taskTest();

r($task->checkRequired4BatchCreateTest($executionIdList[0], $withoutStoryTask, true))            && p('message:0')  && e('『相关研发需求』不能为空。');                         // 测试短期项目批量创建未关联需求的任务
r($task->checkRequired4BatchCreateTest($executionIdList[3], $withoutStoryTask, true))            && p('1:name')     && e('task1');                                              // 测试运维迭代批量创建未关联需求的任务
r($task->checkRequired4BatchCreateTest($executionIdList[4], $requestStageTask, true))            && p('1:name')     && e('task2');                                              // 测试需求类型阶段批量创建未关联需求的任务
r($task->checkRequired4BatchCreateTest($executionIdList[5], $reviewStageTask, true))             && p('1:name')     && e('task3');                                              // 测试总结评审类型阶段批量创建未关联需求的任务
r($task->checkRequired4BatchCreateTest($executionIdList[6], $devStageTask, true))                && p('message:0')  && e('『相关研发需求』不能为空。');                         // 测试开发类型阶段批量创建未关联需求的任务
r($task->checkRequired4BatchCreateTest($executionIdList[0], $conformLimitedTask, false, true))   && p('deadline:0') && e('任务截止日期应小于等于执行的结束日期：2023-02-01。'); // 测试开启起止日期限制，创建的任务截止日期大于执行的截止日期
r($task->checkRequired4BatchCreateTest($executionIdList[0], $unConformLimitedTask, false, true)) && p('1:name')     && e('task1');                                              // 测试开启起止日期限制，创建的任务截止日期小于执行的截止日期
r($task->checkRequired4BatchCreateTest($executionIdList[0], $deadlineSmallTask))                 && p('message:0')  && e('"截止日期"必须大于"预计开始"');                       // 测试任务截止日期小于任务的截止日期
r($task->checkRequired4BatchCreateTest($executionIdList[0], $estimateNumberTask))                && p('message:0')  && e('"最初预计"必须为正数');                               // 测试任务预计工时不为数字的情况
r($task->checkRequired4BatchCreateTest($executionIdList[0], $negativeNumberTask))                && p('message:0')  && e('"最初预计"必须为正数');                               // 测试任务预计工时为负数的情况
