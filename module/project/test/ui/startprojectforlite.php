#!/usr/bin/env php
<?php

/**

title=运营界面启动项目测试
timeout=0
cid=73

- 启动运营项目
 - 测试结果 @启动运营项目成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/startprojectforlite.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->hasProduct->range('0');
$project->name->range('运营项目1');
$project->path->range('`,1,`');
$project->vision->range('lite');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$tester = new startProjectForLiteTester();
$tester->login();

r($tester->startProject()) && p('message,status') && e('启动运营项目成功,SUCCESS'); //启动运营项目

$tester->closeBrowser();
