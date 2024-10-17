#!/usr/bin/env php
<?php

/**
title=执行下bug列表操作检查
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/bug.ui.class.php';
global $config;

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1, 1');
$project->model->range('scrum, []{2}');
$project->type->range('project, sprint{2}');
$project->auth->range('extend, []{2}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3, 1-3');
$projectProduct->product->range('1{3}, 2{3}');
$projectProduct->gen(6);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0');
$user->account->range('admin, user1, user2');
$user->realname->range('admin, USER1, USER2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(3);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{3}, 2{3}');
$team->type->range('project{3}, execution{3}');
$team->account->range('admin, user1, user2, admin, user1, user2');
$team->gen(6);

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->project->range('1');
$bug->product->range('1{9}, 2{9}');
$bug->execution->range('2');
$bug->title->range('1-100');
$bug->status->range('active{2}, resolved{3}, closed{5}, active{2}, resolved{3}, closed{5}');
$bug->assignedTo->range('[]');
$bug->gen(18);

$tester = new bugTester();
$tester->login();

/* 检查标签下统计数据 */
r($tester->checkTab('allTab', '18'))       && p('status,message') && e('SUCCESS,allTab下显示条数正确');
r($tester->checkTab('unresolvedTab', '4')) && p('status,message') && e('SUCCESS,unresolvedTab下显示条数正确');
/* 指派bug */
r($tester->assignTo('USER1')) && p('status,message') && e('SUCCESS,指派bug成功');
r($tester->batchAssignTo())   && p('status,message') && e('SUCCESS,批量指派bug成功');
/* 切换产品 */
r($tester->switchProduct('firstProduct', '18')) && p('status,message') && e('SUCCESS,切换产品查看bug成功');
r($tester->switchProduct('secondProduct', '9')) && p('status,message') && e('SUCCESS,切换产品查看bug成功');
r($tester->switchProduct('thirdProduct', '9'))  && p('status,message') && e('SUCCESS,切换产品查看bug成功');
$tester->closeBrowser();
