#!/usr/bin/env php
<?php

/**
title=创建空间
timeout=0
cid=1

- 执行tester模块的createSpace方法，参数是'myspace', ''▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @空间名称为空时提示正确
- 执行tester模块的createSpace方法，参数是'myspace', '空间1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @空间创建成功
- 执行tester模块的createSpace方法，参数是'teamspace', ''▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @空间名称为空时提示正确
- 执行tester模块的createSpace方法，参数是'teamspace', '空间2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @空间创建成功

*/

chdir(__DIR__);
include '../lib/ui/createspace.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->PO->range('admin, []');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0');
$project->model->range('scrum');
$project->type->range('project');
$project->auth->range('extend');
$project->storyType->range('story');
$project->parent->range('0');
$project->path->range('`,1,`, `,2,`, `,3,`');
$project->grade->range('1');
$project->name->range('项目1, 项目2, 项目3');
$project->begin->range('(-6M)-(-5M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5M)-(+6M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('admin, user1, user2');
$project->acl->range('open');
$project->status->range('wait');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(3);

$tester = new createSpaceTester();
$tester->login();

r($tester->createSpace('myspace', ''))         && p('status,message') && e('SUCCESS,空间名称为空时提示正确');
r($tester->createSpace('myspace', '空间1'))    && p('status,message') && e('SUCCESS,空间创建成功');
r($tester->createSpace('teamspace', ''))       && p('status,message') && e('SUCCESS,空间名称为空时提示正确');
r($tester->createSpace('teamspace', '空间2'))  && p('status,message') && e('SUCCESS,空间创建成功');
$tester->closeBrowser();
