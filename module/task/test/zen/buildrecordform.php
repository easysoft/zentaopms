#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildRecordForm();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性taskID @1
 - 属性title @任务日志
- 步骤2：团队模式排序处理
 - 属性taskID @1
 - 属性from @execution
 - 属性orderBy @order_desc
- 步骤3：默认折叠状态
 - 属性taskID @2
 - 属性taskEffortFold @0
- 步骤4：当前用户分配的任务
 - 属性taskID @1
 - 属性taskAssignedTo @admin
 - 属性taskEffortFold @1
- 步骤5：用户列表验证
 - 属性taskID @3
 - 属性from @kanban
 - 属性usersCount @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1');
$project->name->range('测试项目');
$project->type->range('project');
$project->status->range('doing');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('2');
$execution->parent->range('1');
$execution->name->range('测试执行');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->gen(1);

$story = zenData('story');
$story->id->range('1');
$story->title->range('测试需求');
$story->type->range('story');
$story->status->range('active');
$story->version->range('1');
$story->gen(1);

$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1');
$task->execution->range('2');
$task->name->range('测试任务{1-10}');
$task->type->range('devel');
$task->mode->range('linear{5},');
$task->assignedTo->range('admin{3},user1{3},user2{4}');
$task->status->range('wait{3},doing{4},done{3}');
$task->story->range('0{8},1{2}');
$task->storyVersion->range('1');
$task->gen(10);

$taskTeam = zenData('taskteam');
$taskTeam->id->range('1-15');
$taskTeam->task->range('1{3},2{3},3{3},4{3},5{3}');
$taskTeam->account->range('admin,user1,user2');
$taskTeam->status->range('wait{5},doing{5},done{5}');
$taskTeam->gen(15);

$taskEstimate = zenData('taskestimate');
$taskEstimate->id->range('1-20');
$taskEstimate->task->range('1{4},2{4},3{4},4{4},5{4}');
$taskEstimate->account->range('admin,user1,user2,user3');
$taskEstimate->consumed->range('1.0{5},2.0{5},3.0{5},1.5{5}');
$taskEstimate->left->range('0.5{5},1.0{5},0{5},2.0{5}');
$taskEstimate->gen(20);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户一,用户二,用户三,用户四,用户五,用户六,用户七,用户八,用户九');
$user->deleted->range('0');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->buildRecordFormTest(1, '', '')) && p('taskID,title') && e('1,任务日志'); // 步骤1：正常情况
r($taskTest->buildRecordFormTest(1, 'execution', 'order_desc')) && p('taskID,from,orderBy') && e('1,execution,order_desc,id_desc'); // 步骤2：团队模式排序处理
r($taskTest->buildRecordFormTest(2, '', '')) && p('taskID,taskEffortFold') && e('2,0'); // 步骤3：默认折叠状态
r($taskTest->buildRecordFormTest(1, '', '')) && p('taskID,taskAssignedTo,taskEffortFold') && e('1,admin,1'); // 步骤4：当前用户分配的任务
r($taskTest->buildRecordFormTest(3, 'kanban', 'date_asc')) && p('taskID,from,usersCount') && e('3,kanban,10'); // 步骤5：用户列表验证