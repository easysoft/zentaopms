#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project')->gen('10');
zdTable('kanbancell')->config('kanbancell')->gen('10');

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

title=测试taskModel->batchCreate();
cid=1
pid=1


*/

$executionIdList = array(2);

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
$estimate      = array('1', '2', '3', '1', '1');
$estStarted    = array('2022-07-01', '2023-05-08', '', '2023-09-09', '');
$deadline      = array('2023-02-01', '2022-06-02', '', '', '');
$desc          = array('', 'taskDesc', 'taskAAA', '', '');
$pri           = array('1', '2', '3', '3', '1');

$withoutNameTask = array();
$withoutNameTask[1] = new stdclass();
$withoutNameTask[1]->execution  = $executionIdList[0];
$withoutNameTask[1]->parent     = $parent[0];
$withoutNameTask[1]->module     = $module[0];
$withoutNameTask[1]->name       = $name[4];
$withoutNameTask[1]->deadline   = $deadline[0];
$withoutNameTask[1]->estimate   = $estimate[0];
$withoutNameTask[1]->estStarted = $estStarted[0];

$normalTaskList = array();
$normalTaskList[1] = new stdclass();
$normalTaskList[1]->execution  = $executionIdList[0];
$normalTaskList[1]->parent     = $parent[0];
$normalTaskList[1]->module     = $module[0];
$normalTaskList[1]->story      = $story[0];
$normalTaskList[1]->name       = $name[0];
$normalTaskList[1]->version    = 1;
$normalTaskList[1]->type       = $type[0];
$normalTaskList[1]->deadline   = $deadline[0];
$normalTaskList[1]->estimate   = $estimate[0];
$normalTaskList[1]->estStarted = $estStarted[0];
$normalTaskList[1]->mailto     = '';

$normalTaskList[2] = new stdclass();
$normalTaskList[2]->execution  = $executionIdList[0];
$normalTaskList[2]->parent     = $parent[1];
$normalTaskList[2]->module     = $module[1];
$normalTaskList[2]->story      = $story[1];
$normalTaskList[2]->name       = $name[1];
$normalTaskList[2]->color      = $color[1];
$normalTaskList[2]->type       = $type[1];
$normalTaskList[2]->version    = 1;
$normalTaskList[2]->assignedTo = $assignedTo[1];
$normalTaskList[2]->estimate   = $estimate[1];
$normalTaskList[2]->estStarted = $estStarted[1];
$normalTaskList[2]->deadline   = $deadline[2];
$normalTaskList[2]->desc       = $desc[1];
$normalTaskList[2]->pri        = $pri[1];
$normalTaskList[2]->mailto     = '';

$hasParentTask = array();
$hasParentTask[1] = new stdclass();
$hasParentTask[1]->execution  = $executionIdList[0];
$hasParentTask[1]->parent     = $parent[4];
$hasParentTask[1]->module     = $module[1];
$hasParentTask[1]->story      = $story[1];
$hasParentTask[1]->name       = $name[1];
$hasParentTask[1]->color      = $color[1];
$hasParentTask[1]->type       = $type[1];
$hasParentTask[1]->version    = 1;
$hasParentTask[1]->assignedTo = $assignedTo[1];
$hasParentTask[1]->estimate   = $estimate[1];
$hasParentTask[1]->estStarted = $estStarted[1];
$hasParentTask[1]->deadline   = $deadline[2];
$hasParentTask[1]->desc       = $desc[1];
$hasParentTask[1]->pri        = $pri[1];
$hasParentTask[1]->mailto     = '';

$hasStoryTask = array();
$hasStoryTask[1] = new stdclass();
$hasStoryTask[1]->execution  = $executionIdList[0];
$hasStoryTask[1]->parent     = $parent[0];
$hasStoryTask[1]->module     = $module[1];
$hasStoryTask[1]->story      = $story[0];
$hasStoryTask[1]->name       = $name[1];
$hasStoryTask[1]->color      = $color[1];
$hasStoryTask[1]->type       = $type[1];
$hasStoryTask[1]->version    = 1;
$hasStoryTask[1]->assignedTo = $assignedTo[1];
$hasStoryTask[1]->estimate   = $estimate[1];
$hasStoryTask[1]->estStarted = $estStarted[1];
$hasStoryTask[1]->deadline   = $deadline[2];
$hasStoryTask[1]->desc       = $desc[1];
$hasStoryTask[1]->pri        = $pri[1];
$hasStoryTask[1]->mailto     = '';

$verifyScoreTask = array();
$verifyScoreTask[1] = new stdclass();
$verifyScoreTask[1]->execution  = $executionIdList[0];
$verifyScoreTask[1]->parent     = $parent[4];
$verifyScoreTask[1]->module     = $module[1];
$verifyScoreTask[1]->story      = $story[1];
$verifyScoreTask[1]->name       = $name[1];
$verifyScoreTask[1]->color      = $color[1];
$verifyScoreTask[1]->type       = $type[1];
$verifyScoreTask[1]->version    = 1;
$verifyScoreTask[1]->assignedTo = $assignedTo[1];
$verifyScoreTask[1]->estimate   = $estimate[1];
$verifyScoreTask[1]->estStarted = $estStarted[1];
$verifyScoreTask[1]->deadline   = $deadline[2];
$verifyScoreTask[1]->desc       = $desc[1];
$verifyScoreTask[1]->pri        = $pri[1];
$verifyScoreTask[1]->mailto     = '';

$kanbanTask = array();
$kanbanTask[1] = new stdclass();
$kanbanTask[1]->execution  = $executionIdList[0];
$kanbanTask[1]->parent     = $parent[4];
$kanbanTask[1]->module     = $module[1];
$kanbanTask[1]->story      = $story[1];
$kanbanTask[1]->name       = $name[3];
$kanbanTask[1]->color      = $color[1];
$kanbanTask[1]->type       = $type[1];
$kanbanTask[1]->version    = 1;
$kanbanTask[1]->assignedTo = $assignedTo[1];
$kanbanTask[1]->estimate   = $estimate[1];
$kanbanTask[1]->estStarted = $estStarted[1];
$kanbanTask[1]->deadline   = $deadline[2];
$kanbanTask[1]->desc       = $desc[1];
$kanbanTask[1]->pri        = $pri[1];
$kanbanTask[1]->mailto     = '';

$task = new taskTest();

r($task->batchCreateObject($withoutNameTask, $executionIdList[0]))                                                                            && p('message:0') && e('『任务名称』不能为空。'); // 测试任务名称为空的情况
r($task->batchCreateObject($normalTaskList, $executionIdList[0]))                                                                             && p('')          && e('2');                      // 测试正常任务批量创建成功
r($task->batchCreateObject($hasParentTask, $executionIdList[0], $parent[4]))                                                                  && p('name')      && e('任务1');                  // 测试拆分任务
r($task->batchCreateObject($hasStoryTask, $executionIdList[0], 0, $story[0]))                                                                 && p('title')     && e('需求1');                  // 测试关联需求的情况
r($task->batchCreateObject($verifyScoreTask, $executionIdList[0], 0, 0, true))                                                                && p('')          && e('1');                      // 测试是否正常创建积分记录
r($task->batchCreateObject($kanbanTask, $executionIdList[0], 0, 0, false, array('laneID' => $laneIdList[0], 'columnID' => $columnIdList[0]))) && p('name')      && e('task4');                  // 测试在看板的泳道列批量创建任务
