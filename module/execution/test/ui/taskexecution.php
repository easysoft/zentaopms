#!/usr/bin/env php
<?php

/*
title=执行下的任务列表页面
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/taskexecution.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->gen(1);

$project = zenData('project');
$project->id->range('1, 2');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->name->range('项目1, 项目1执行1');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2');
$projectProduct->product->range('1');
$projectProduct->gen(2);

$story = zenData('story');
$story->id->range('1');
$story->path->range('`,1,`');
$story->product->range('1');
$story->title->range('产品1研发需求1版本2');
$story->type->range('story');
$story->status->range('active');
$story->version->range('2');
$story->gen(1);

$storySpec = zenData('storyspec');
$storySpec->story->range('1');
$storySpec->version->range('1,2');
$storySpec->title->range('产品1研发需求1版本1, 产品1研发需求1版本2');
$storySpec->gen(2);

$projectStory = zenData('projectstory');
$projectStory->project->range('1, 2');
$projectStory->product->range('1');
$projectStory->story->range('1');
$projectStory->version->range('1');
$projectStory->gen(2);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1, 2');
$team->type->range('project, execution');
$team->account->range('admin, user1, user2');
$team->gen(6);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->execution->range('2');
$task->story->range('1, 0{99}');
$task->storyVersion->range('1');
$task->name->range('1-100');
$task->type->range('devel');
$task->deadline->range(' (-5D)-(-4D):1D, []{11}')->type('timestamp')->format('YY/MM/DD');
$task->status->range('wait{3}, doing{3}, done{2}, cancel, closed{3}');
$task->openedBy->range('admin{6}, user1{6}');
$task->assignedTo->range('[]{3}, user1{4}, admin{2}, closed{3}');
$task->finishedBy->range('[]{6}, admin, user1, [], admin, user1{2}');
$task->canceledBy->range('[]{8}, admin, []{3}');
$task->closedBy->range('[]{9}, admin{2}, user1');
$task->deleted->range('0{11}, 1');
$task->gen(12);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(12);

$action = zenData('action');
$action->id->range('1,2');
$action->objectType->range('task');
$action->objectID->range('4, 5');
$action->project->range('1');
$action->execution->range('2');
$action->actor->range('admin, user1');
$action->action->range('assigned');
$action->extra->range('user1');
$action->gen(2);

$tester = new taskExecutionTester();
$tester->login();

/* 检查标签下显示条数 */
r($tester->checkTab('allTab', '11'))         && p('status,message') && e('SUCCESS,allTab下显示条数正确');          //检查全部标签下显示条数
r($tester->checkTab('unclosedTab', '9'))     && p('status,message') && e('SUCCESS,unclosedTab下显示条数正确');     //检查未关闭标签下显示条数
r($tester->checkTab('assignedtomeTab', '2')) && p('status,message') && e('SUCCESS,assignedtomeTab下显示条数正确'); //检查指派给我标签下显示条数
r($tester->checkTab('myInvolvedTab', '4'))   && p('status,message') && e('SUCCESS,myInvolvedTab下显示条数正确');   //检查由我参与标签下显示条数
r($tester->checkTab('assignedByMeTab', '1')) && p('status,message') && e('SUCCESS,assignedByMeTab下显示条数正确'); //检查由我指派标签下显示条数
r($tester->checkTab('needConfirmTab', '1'))  && p('status,message') && e('SUCCESS,needConfirmTab下显示条数正确');  //检查研发需求变更标签下显示条数
r($tester->checkTab('waitingTab', '3'))      && p('status,message') && e('SUCCESS,waitingTab下显示条数正确');      //检查未开始标签下显示条数
r($tester->checkTab('doingTab', '3'))        && p('status,message') && e('SUCCESS,doingTab下显示条数正确');        //检查进行中标签下显示条数
r($tester->checkTab('undoneTab', '7'))       && p('status,message') && e('SUCCESS,undoneTab下显示条数正确');        //检查未完成标签下显示条数
r($tester->checkTab('finushedByMeTab', '2')) && p('status,message') && e('SUCCESS,finushedByMeTab下显示条数正确'); //检查我完成标签下显示条数
r($tester->checkTab('doneTab', '2'))         && p('status,message') && e('SUCCESS,doneTab下显示条数正确');         //检查已完成标签下显示条数
r($tester->checkTab('closedTab', '2'))       && p('status,message') && e('SUCCESS,closedTab下显示条数正确');       //检查已关闭标签下显示条数
r($tester->checkTab('cancelTab', '1'))       && p('status,message') && e('SUCCESS,cancelTab下显示条数正确');       //检查已取消标签下显示条数
r($tester->checkTab('delayedTab', '1'))      && p('status,message') && e('SUCCESS,delayedTab下显示条数正确');      //检查已延期标签下显示条数
/* 批量修改状态 */
r($tester->batchEditStatus('closed'))        && p('status,message') && e('SUCCESS,批量修改状态为closed成功');
r($tester->batchEditStatus('cancel'))        && p('status,message') && e('SUCCESS,批量修改状态为cancel成功');
/* 批量指派 */
r($tester->batchAssign())                    && p('status,message') && e('SUCCESS,批量指派成功');
$tester->closeBrowser();
