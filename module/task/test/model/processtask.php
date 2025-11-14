#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=taskModel->processTask();
timeout=0
cid=18839

- 计算一个不存在的任务进度 @任务未找到

- 根据taskID计算wait的任务进度
 - 属性productType @normal
 - 属性progress @75

- 根据taskID计算wait的任务进度及任务团队
 - 属性productType @branch
 - 属性progress @80
 - 属性teamMembers @用户1,用户2,用户3,用户4,用户5

- 根据taskID计算doing的任务进度
 - 属性productType @platform
 - 属性progress @83

- 根据taskID计算done的任务进度
 - 属性productType @normal
 - 属性progress @100

- 根据taskID计算pause的任务进度
 - 属性productType @branch
 - 属性progress @88

*/

$task = zenData('task');
$task->id->range('1-7');
$task->execution->range('1-7');
$task->name->prefix("任务")->range('1-7');
$task->left->range('1,1,1,0,1,0,0');
$task->story->range('1-5');
$task->storyVersion->range('1,2,4,4,5');
$task->mode->range(" , multi, , , , ,");
$task->estStarted->range('2022\-01\-01');
$task->deadline->range('2022\-01\-01');
$task->assignedTo->prefix("user")->range('1-7');
$task->status->range("wait,wait,doing,done,pause,cancel,closed");
$task->gen(5);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1-5');
$story->title->prefix('需求')->range('1-5');
$story->status->range('active');
$story->version->range('1-5');
$story->gen(5);

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

$task = new taskTest();
r($task->processTaskTest(0)) && p() && e('任务未找到'); //计算一个不存在的任务进度
r($task->processTaskTest(1)) && p('productType,progress') && e('normal,75');   //根据taskID计算wait的任务进度
r($task->processTaskTest(2)) && p('productType|progress|teamMembers', '|') && e('branch|80|用户1,用户2,用户3,用户4,用户5'); //根据taskID计算wait的任务进度及任务团队
r($task->processTaskTest(3)) && p('productType,progress') && e('platform,83'); //根据taskID计算doing的任务进度
r($task->processTaskTest(4)) && p('productType,progress') && e('normal,100');  //根据taskID计算done的任务进度
r($task->processTaskTest(5)) && p('productType,progress') && e('branch,88');   //根据taskID计算pause的任务进度