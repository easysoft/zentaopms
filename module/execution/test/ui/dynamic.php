#!/usr/bin/env php
<?php

/**
title=执行动态
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/dynamic.ui.class.php';
global $config;

$user = zenData('user');
$user->id->range('1-100');
$user->account->range('admin, user1, user2, user3, user4, user5');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(6);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{2}');
$project->model->range('scrum, []');
$project->type->range('project, sprint{2}');
$project->auth->range('extend, []{2}');
$project->storyType->range('story, []{2}');
$project->parent->range('0, 1{2}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('项目, 执行1, 执行2');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1, 2');
$projectproduct->product->range('1');
$projectproduct->gen(2);

$team = zenData('team');
$team->root->range('1{3}, 2{3}, 3{3}');
$team->type->range('project{3}, execution{6}');
$team->account->range('user1, user2, admin');
$team->gen(9);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->execution->range('2, 3{100}');
$task->name->range('1-100');
$task->status->range('wait');
$task->gen(3);

$taskSpec = zenData('taskspec');
$taskSpec->task->range('1-100');
$taskSpec->version->range('0');
$taskSpec->name->range('1-100');
$taskSpec->gen(3);

$actionrecent = zenData('actionrecent');
$actionrecent->id->range('1-100');
$actionrecent->objectType->range('task');
$actionrecent->objectID->range('1, 2, 3{100}');
$actionrecent->product->range('1');
$actionrecent->project->range('1');
$actionrecent->execution->range('2, 3{100}');
$actionrecent->actor->range('admin{2}, user1, user2{100}');
$actionrecent->action->range('edited');
$actionrecent->date->range('-:60')->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$actionrecent->gen(10);

$action = zenData('action');
$action->objectType->range('task');
$action->objectID->range('1, 2, 3{100}');
$action->product->range('1');
$action->project->range('1');
$action->execution->range('2, 3{100}');
$action->actor->range('admin{2}, user1, user2{100}');
$action->action->range('edited');
$action->date->range('-:60')->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$action->gen(10);

$tester = new dynamicTester();
$tester->login();
