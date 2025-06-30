#!/usr/bin/env php
<?php

/**
title=运营管理界面开始看板
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/startinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{2}');
$project->model->range('kanban, []{2}');
$project->type->range('project, kanban{2}');
$project->auth->range('[]');
$project->storyType->range('[]');
$project->parent->range('0, 1{2}');
$project->path->range('`,1,`, `,1,2,`');
$project->grade->range('1');
$project->name->range('项目, 看板1, 看板2');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('user1');
$project->acl->range('open');
$project->status->range('wait');
$project->vision->range('lite');
$project->gen(3);

$tester = new startExecutionTester();
$tester->login();

$realBegan = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'));

r($tester->startWithGreaterDate($realBegan[0], 2)) && p('status,message') && e('SUCCESS,开始看板表单页提示信息正确');
r($tester->start($realBegan[1], 2))                && p('status,message') && e('SUCCESS,开始看板成功');
$tester->closeBrowser();
