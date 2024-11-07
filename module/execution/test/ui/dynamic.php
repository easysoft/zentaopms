#!/usr/bin/env php
<?php

/**
title=执行动态
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/dynamic.ui.class.php';

$user = zenData('user');
$user->id->range('1-100');
$user->account->range('admin, user1, user2, user3, user4, user5');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(6);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1');
$project->model->range('scrum, []');
$project->type->range('project, sprint');
$project->auth->range('extend, []');
$project->storyType->range('story, []');
$project->parent->range('0, 1');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('项目, 执行');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(2);
