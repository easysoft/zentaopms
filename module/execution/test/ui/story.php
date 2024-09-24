<?php

/**
title=执行下需求列表操作检查
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/story.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0, 1, 1, 0, 4');
$project->model->range('scrum, []{2}, scrum, []');
$project->type->range('project, sprint{2}, project, sprint');
$project->auth->range('extend, []{2}, extend, []');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,4,`, `,4,5,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目2, 项目2执行1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-5');
$projectProduct->product->range('1');
$projectProduct->gen(5);

$story = zenData('story');
$story->id->range('1-100');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-15');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`, `,8,`, `,9,`, `,10,`, `,11,`, `,12,`, `,13,`, `,14,`, `,15,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('1-15');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active{3}, closed{3}, reviewing{3}, draft{3}, changing{3}');
$story->stage->range('projected');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(15);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-15');
$storySpec->version->range('1');
$storySpec->title->range('1-15');
$storySpec->gen(15);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{15}, 2{15}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-15, 1-15');
$projectStory->version->range('1');
$projectStory->order->range('1{15}, 2{15}');
$projectStory->gen(30);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0');
$user->account->range('admin, user1, user2');
$user->realname->range('admin, USER1, USER2');
$user->password->range('77839ef72f7b71a3815a77d038e267e0');
$user->gen(3);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{3}, 2{2}');
$team->type->range('project{2}, execution{2}');
$team->account->range('user1, user2, user1, user2');
$team->gen(4);


$tester = new storyTester();
$tester->login();

/* 标签统计 */
r($tester->checkTab('allTab', '15'))      && p('message') && e('allTab下显示条数正确');       //检查全部标签下显示条数
r($tester->checkTab('unclosedTab', '12')) && p('message') && e('unclosedTab下显示条数正确');  //检查未关闭标签下显示条数
r($tester->checkTab('draftTab', '3'))     && p('message') && e('draftTab下显示条数正确');     //检查草稿标签下显示条数
r($tester->checkTab('reviewingTab', '3')) && p('message') && e('reviewingTab下显示条数正确'); //检查评审中标签下显示条数
/* 移除需求 */
r($tester->unlinkStory())       && p('message') && e('需求移除成功');     //移除需求
r($tester->batchUnlinkStory())  && p('message') && e('需求批量移除成功'); //批量移除需求
/* 批量编辑阶段 */
r($tester->batchEditPhase('draft', 'testing'))    && p('status,message') && e('success,批量编辑draft阶段成功');     //编辑草稿状态的需求的阶段为测试中
r($tester->batchEditPhase('reviewing', 'wait'))   && p('status,message') && e('success,批量编辑reviewing阶段成功'); //编辑评审中状态的需求的阶段为未开始
r($tester->batchEditPhase('active', 'verified'))  && p('status,message') && e('success,批量编辑active阶段成功');    //编辑激活状态的需求的阶段为已验收
r($tester->batchEditPhase('changing', 'planned')) && p('status,message') && e('success,批量编辑changing阶段成功');  //编辑变更中状态的需求的阶段为已计划
r($tester->batchEditPhase('closed', 'rejected'))  && p('status,message') && e('success,批量编辑closed阶段成功');    //编辑已关闭状态的需求的阶段为验收失败
/* 指派 */
r($tester->assignTo('USER1'))  && p('message') && e('指派成功');     //单个指派
r($tester->batchAssignTo())    && p('message') && e('批量指派成功'); //批量指派
$tester->closeBrowser();
