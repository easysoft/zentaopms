#!/usr/bin/env php
<?php

/**
title=执行看板
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/executionkanban.ui.class.php';
global $config;

$project = zenData('project');
$project->id->range('1-4');
$project->project->range('0');
$project->model->range('scrum{4}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('项目1, 项目2, 项目3, 项目4');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait{2}, doing, closed');
$project->gen(4);

$execution = zenData('project');
$execution->id->range('5-100');
$execution->project->range('1{2}, 2{3}, 3{8}, 4{9}');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1{2}, 2{3}, 3{8}, 4{9}');
$execution->grade->range('1');
$execution->name->range('5-100');
$execution->path->range('`,1,5,`, `,1,6,`, `,2,7,`, `,2,8,`, `,2,9,`, `,3,10,`, `,3,11,`, `,3,12,`, `,3,13,`, `,3,14,`, `,3,15,`, `,3,16,`, `,3,17,`, `,4,18,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->openedBy->range('user1');
$execution->acl->range('open');
$execution->status->range('closed, wait{4}, doing{2}, suspended{3}, closed{4}');
$execution->closedDate->range('(-2w)-(-w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->gen(14, false);

$user = zenData('user');
$user->id->range('1-100');
$user->account->range('admin, user1');
$user->realname->range('admin, USER1');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(2);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1-2, 4-7, 10-18');
$team->type->range('project{2}, execution{100}');
$team->account->range('admin');
$team->gen(15);
