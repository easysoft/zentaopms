#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=taskModel->processTask();
timeout=0
cid=18840

- 查看处理后的执行1下的任务数量 @6

- 根据executionID计算其下任务的进度及相关信息
 - 第1条的productType属性 @normal
 - 第1条的progress属性 @75

- 根据executionID计算其下任务的进度及相关信息
 - 第2条的productType属性 @branch
 - 第2条的progress属性 @80

- 根据executionID计算其下任务的进度及相关信息
 - 第3条的productType属性 @platform
 - 第3条的progress属性 @83

- 根据executionID计算其下任务的进度及相关信息
 - 第4条的productType属性 @normal
 - 第4条的progress属性 @100

- 根据executionID计算其下任务的进度及相关信息
 - 第5条的productType属性 @branch
 - 第5条的progress属性 @88

*/

$task = zenData('task');
$task->id->range('1-30');
$task->execution->range('1-5');
$task->name->prefix("任务")->range('1-30');
$task->left->range('1,1,1,0,1,0,0');
$task->story->range('1-30');
$task->storyVersion->range('1,2,4,4,5');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->deadline->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(30);

$execution = zenData('project');
$execution->id->range('1-5');
$execution->type->range('sprint');
$execution->name->prefix('执行')->range('1-5');
$execution->gen(5);

$story = zenData('story');
$story->id->range('1-30');
$story->product->range('1-5');
$story->title->prefix('需求')->range('1-5');
$story->status->range('active');
$story->version->range('1-5');
$story->gen(30);

$product = zenData('product');
$product->id->range('1-5');
$product->name->prefix('产品')->range('1-5');
$product->type->range('normal,branch,platform');
$product->status->range('normal,closed');
$product->gen(5);

$taskteam = zenData('taskteam');
$taskteam->id->range('1-5');
$taskteam->task->range('2');
$taskteam->account->prefix("user")->range('1-5');
$taskteam->estimate->range('5');
$taskteam->consumed->range('0');
$taskteam->left->range('5');
$taskteam->status->range("wait");
$taskteam->gen(5);

$effort = zenData('effort');
$effort->gen(1);

$user = zenData('user');
$user->gen(20);

$task = new taskModelTest();
r(count($task->processTasksTest(1))) && p() && e('6'); //查看处理后的执行1下的任务数量
r($task->processTasksTest(1)) && p('1:productType,progress') && e('normal,75');   //根据executionID计算其下任务的进度及相关信息
r($task->processTasksTest(2)) && p('2:productType,progress') && e('branch,80');   //根据executionID计算其下任务的进度及相关信息
r($task->processTasksTest(3)) && p('3:productType,progress') && e('platform,83'); //根据executionID计算其下任务的进度及相关信息
r($task->processTasksTest(4)) && p('4:productType,progress') && e('normal,100');  //根据executionID计算其下任务的进度及相关信息
r($task->processTasksTest(5)) && p('5:productType,progress') && e('branch,88');   //根据executionID计算其下任务的进度及相关信息