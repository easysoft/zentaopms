#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/task.ui.class.php';

/**

title=开源版user模块视图层task界面测试
timeout=0
cid=1

- 开源版user模块视图层task界面测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版user模块视图层task界面测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$taskspec = zenData('taskspec');
$taskspec->task->range('1-30');
$taskspec->version->range('1');
$taskspec->name->range('1-30')->prefix('任务');
$taskspec->gen(30);

$story = zenData('story');
$story->id->range('1-10');
$story->title->range('1-10')->prefix('故事');
$story->status->range('active');
$story->version->range('1');
$story->vision->range('rnd');
$story->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('1-5')->prefix('产品');
$product->code->range('1-5')->prefix('code');
$product->type->range('normal');
$product->status->range('normal');
$product->createdBy->range('admin');
$product->createdDate->range('`2025-10-01`');
$product->vision->range('rnd');
$product->order->range('1-5');
$product->gen(5);

// 项目数据：project 主项目 + execution 执行（使用同一张 zt_project 表）
$projectMain = zenData('project');
$projectMain->id->range('1');
$projectMain->name->range('项目1');
$projectMain->type->range('project');
$projectMain->multiple->range('1');
$projectMain->vision->range('rnd');
$projectMain->isTpl->range('0');
$projectMain->status->range('doing');
$projectMain->gen(1);

$execution = zenData('project');
$execution->id->range('6-10');
$execution->name->range('6-10')->prefix('迭代');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->multiple->range('1');
$execution->vision->range('rnd');
$execution->isTpl->range('0');
$execution->status->range('doing');
$execution->gen(5, false);

// 将 admin 作为执行成员加入所有迭代，便于页面关联成员信息与权限
$team = zenData('team');
$team->id->range('1-5');
$team->root->range('6,7,8,9,10'); //sprints
$team->type->range('execution');
$team->account->range('admin');
$team->role->range('dev');
$team->join->range('`2025-10-01`');
$team->days->range('10');
$team->hours->range('8');
$team->gen(5);

// 用户视图：将产品/项目/迭代挂到 admin 的视图中
$userview = zenData('userview');
$userview->account->range('admin');
$userview->products->range('1,2,3,4,5');
$userview->projects->range('1');
$userview->sprints->range('6,7,8,9,10');
$userview->gen(1);

// 使用zenData生成任务数据 - 为admin、user1、user2各生成10条
$task = zenData('task');
$task->id->range('1-30');
$task->name->range('1-30')->prefix('任务');
$task->type->range('devel,test,design,study,discuss,ui,affair,misc');
$task->pri->range('1,2,3,4');
$task->estimate->range('1,2,4,8,16');
$task->consumed->range('0,1,2,4');
$task->left->range('0,1,2,4,8');
$task->status->range('done{2},closed{2},wait{2},doing{2},pause{1},cancel{1}');
$task->color->range('');
$task->desc->range('1-30')->prefix('这是任务描述');
$task->project->range('1'); // 与执行的 project 字段一致，便于显示与查询
$task->execution->range('6-10');
$task->story->range('1-10,0{10}');
$task->storyVersion->range('1');
$task->assignedTo->range('admin{10},user1{10},user2{10}');
$task->assignedDate->range('`2025-10-01 09:00:00`');
$task->openedBy->range('admin{10},user1{10},user2{10}');
$task->openedDate->range('`2025-11-01 09:00:00`');
$task->finishedBy->range('admin{2},{8},user1{2},{8},user2{2},{8}');
$task->finishedDate->range('`2025-10-15 18:00:00`');
$task->closedBy->range('{2},admin{2},{8},user1{2},{8},user2{2},{6}');
$task->closedDate->range('`2025-10-20 18:00:00`');
$task->closedReason->range('{2},done,cancel,{6}');
$task->canceledBy->range('{9},admin{1},{9},user1{1},{9},user2{1}');
$task->canceledDate->range('`2025-10-18 18:00:00`');
$task->lastEditedBy->range('admin');
$task->lastEditedDate->range('`2025-10-02 09:00:00`');
$task->vision->range('rnd');
$task->deadline->range('`2025-10-31`');
$task->mode->range('single{5},multi{5}');
$task->isTpl->range('0');
$task->gen(30);

// 多成员任务团队表：覆盖 SQL 中 t5 的分支
$taskteam = zenData('taskteam');
$taskteam->id->range('1-15');
$taskteam->task->range('6-10,16-20,26-30'); // 对应多成员的任务ID
$taskteam->account->range('admin');
$taskteam->status->range('doing'); // 保证 t5.status != 'done'
$taskteam->gen(15);

global $uiTester;
$users      = $uiTester->dao->select('*')->from('zt_user')->fetchAll();
$tasks      = $uiTester->dao->select('*')->from('zt_task')->fetchAll();
$executions = $uiTester->dao->select('*')->from('zt_project')->fetchAll();
$taskteams  = $uiTester->dao->select('*')->from('zt_taskteam')->fetchAll();

$taskTester = new taskTester();
r($taskTester->verifyUserTaskContentAndPagination($users, $tasks, $executions, $taskteams, 5)) && p('status,message') && e('SUCCESS,开源版user模块视图层task界面测试成功'); // 开源版user模块视图层task界面测试
$taskTester->closeBrowser();
