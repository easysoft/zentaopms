#!/usr/bin/env php
<?php

/**
title=维护执行视图模块
timeout=0
cid=1

- 执行tester模块的createModule方法，参数是'     '▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建模块时模块名包含空格，提示正确
- 执行tester模块的createModule方法，参数是'模块1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建模块成功
- 执行tester模块的createModule方法，参数是'模块1', true▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建模块时模块已存在，提示正确
- 执行tester模块的createModule方法，参数是'模块2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建模块成功
- 执行tester模块的createChildModule方法，参数是' '▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建子模块时子模块名包含空格，提示正确
- 执行tester模块的createChildModule方法，参数是'子模块1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建子模块成功
- 执行tester模块的createChildModule方法，参数是'模块2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建子模块成功
- 执行tester模块的createChildModule方法，参数是'子模块1', true▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @创建子模块时子模块已存在，提示正确
- 执行tester模块的editModule方法，参数是''▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块时模块为空，提示正确
- 执行tester模块的editModule方法，参数是'  '▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块时模块为空，提示正确
- 执行tester模块的editModule方法，参数是'模块2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块时模块已存在，提示正确
- 执行tester模块的editModule方法，参数是'编辑模块1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块成功
- 执行tester模块的deleteModule方法▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @删除模块成功

 */

chdir(__DIR__);
include '../lib/ui/browsetask.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{10}');
$project->model->range('scrum, []{10}');
$project->type->range('project, sprint{10}');
$project->auth->range('extend, []{10}');
$project->storyType->range('story, []{10}');
$project->parent->range('0, 1{10}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`, `,1,5,`, `,1,6,`');
$project->grade->range('1');
$project->name->range('项目, 执行1, 执行2, 执行3, 执行4, 执行5, 执行6');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('admin');
$project->acl->range('open');
$project->status->range('doing');
$project->gen(4);

$projectproduct = zenData('projectproduct');
$projectproduct->gen(0);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('3');
$module->name->range('模块1, 模块2, 子模块1, 子模块2');
$module->parent->range('0{2}, 1{2}');
$module->path->range('`,1,`, `,2,`, `,1,3,`, `,1,4,`');
$module->grade->range('1{2}, 2{2}');
$module->type->range('task');
$module->gen(4);

$tester = new browsetaskTester();
$tester->login();

r($tester->createModule('     '))              && p('status,message') && e('SUCCESS,创建模块时模块名包含空格，提示正确');
r($tester->createModule('模块1'))              && p('status,message') && e('SUCCESS,创建模块成功');
r($tester->createModule('模块1', true))        && p('status,message') && e('SUCCESS,创建模块时模块已存在，提示正确');
r($tester->createModule('模块2'))              && p('status,message') && e('SUCCESS,创建模块成功');
r($tester->createChildModule(' '))             && p('status,message') && e('SUCCESS,创建子模块时子模块名包含空格，提示正确');
r($tester->createChildModule('子模块1'))       && p('status,message') && e('SUCCESS,创建子模块成功');
r($tester->createChildModule('模块2'))         && p('status,message') && e('SUCCESS,创建子模块成功');
r($tester->createChildModule('子模块1', true)) && p('status,message') && e('SUCCESS,创建子模块时子模块已存在，提示正确');

r($tester->editModule(''))          && p('status,message') && e('SUCCESS,编辑模块时模块为空，提示正确');
r($tester->editModule('  '))        && p('status,message') && e('SUCCESS,编辑模块时模块为空，提示正确');
r($tester->editModule('模块2'))     && p('status,message') && e('SUCCESS,编辑模块时模块已存在，提示正确');
r($tester->editModule('编辑模块1')) && p('status,message') && e('SUCCESS,编辑模块成功');

r($tester->deleteModule()) && p('status,message') && e('SUCCESS,删除模块成功');
$tester->closeBrowser();
