#!/usr/bin/env php
<?php

/**
title=维护产品模块
timeout=0
cid=1

- 执行tester模块的createModule方法，参数是' '▫
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
- 执行tester模块的editModule方法，参数是'      '▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块时模块为空，提示正确
- 执行tester模块的editModule方法，参数是'模块2'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块时模块已存在，提示正确
- 执行tester模块的editModule方法，参数是'编辑模块1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @编辑模块成功
- 执行tester模块的copyModule方法，参数是array▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @复制模块时所选产品下没有模块，提示正确
- 执行tester模块的copyModule方法，参数是array▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @复制模块成功
- 执行tester模块的deleteModule方法▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @删除模块成功

 */

chdir(__DIR__);
include '../lib/ui/browse.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->program->range('0');
$product->name->range('产品1, 产品2, 产品3, 产品4');
$product->type->range('normal');
$product->gen(4);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('2');
$module->name->range('模块1, 模块2, 子模块1, 子模块2');
$module->parent->range('0{2}, 1{2}');
$module->path->range('`,1,`, `,2,`, `,1,3,`, `,1,4,`');
$module->grade->range('1{2}, 2{2}');
$module->type->range('story');
$module->gen(4);

$story = zenData('story');
$story->id->range('1-100');
$story->root->range('1');
$story->path->range('`,1,`');
$story->product->range('2');
$story->module->range('3');
$story->title->range('需求1');
$story->type->range('story');
$story->gen(1);

$tester = new browseTester();
$tester->login();

r($tester->createModule(' '))                  && p('status,message') && e('SUCCESS,创建模块时模块名包含空格，提示正确');
r($tester->createModule('模块1'))              && p('status,message') && e('SUCCESS,创建模块成功');
r($tester->createModule('模块1', true))        && p('status,message') && e('SUCCESS,创建模块时模块已存在，提示正确');
r($tester->createModule('模块2'))              && p('status,message') && e('SUCCESS,创建模块成功');
r($tester->createChildModule(' '))             && p('status,message') && e('SUCCESS,创建子模块时子模块名包含空格，提示正确');
r($tester->createChildModule('子模块1'))       && p('status,message') && e('SUCCESS,创建子模块成功');
r($tester->createChildModule('模块2'))         && p('status,message') && e('SUCCESS,创建子模块成功');
r($tester->createChildModule('子模块1', true)) && p('status,message') && e('SUCCESS,创建子模块时子模块已存在，提示正确');

r($tester->editModule(''))          && p('status,message') && e('SUCCESS,编辑模块时模块为空，提示正确');
r($tester->editModule('      '))    && p('status,message') && e('SUCCESS,编辑模块时模块为空，提示正确');
r($tester->editModule('模块2'))     && p('status,message') && e('SUCCESS,编辑模块时模块已存在，提示正确');
r($tester->editModule('编辑模块1')) && p('status,message') && e('SUCCESS,编辑模块成功');

r($tester->copyModule(array('产品4'), false))                  && p('status,message') && e('SUCCESS,复制模块时所选产品下没有模块，提示正确');
r($tester->copyModule(array('产品2', '模块1', '模块2'), true)) && p('status,message') && e('SUCCESS,复制模块成功');

r($tester->deleteModule()) && p('status,message') && e('SUCCESS,删除模块成功');
$tester->closeBrowser();
