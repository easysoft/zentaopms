#!/usr/bin/env php
<?php

/**

title=建场景
timeout=0
cid=73

- 编辑场景必填信息检查
 - 测试结果 @编辑场景必填提示信息正确
 - 最终测试状态 @SUCCESS
- 编辑场景名称重复时提示信息检查
 - 测试结果 @编辑场景名称重复时提示信息正确
 - 最终测试状态 @SUCCESS
- 编辑场景
 - 测试结果 @场景编辑成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/editscene.ui.class.php';

$module = zenData('module');
$module->gen(0);

$scene = zenData('scene');
$scene->gen(0);

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$scene = zenData('scene');
$scene->id->range('1,2');
$scene->module->range('1');
$scene->title->range('场景1,场景2');
$scene->sort->range('1,2');
$scene->openedBy->range('admin');
$scene->grade->range('1,1');
$scene->path->range(',1,',',2,');
$scene->gen(2);

$module = zenData('module');
$module->id->range('1,2');
$module->root->range('1,1');
$module->name->range('模块1,模块2');
$module->parent->range('0');
$module->path->range(',1,',',2,');
$module->grade->range('1,1');
$module->type->range('case');
$module->order->range('10,20');
$module->gen(2);

$tester = new editSceneTester();
$tester->login();

//设置数据
$scene = array(
    '0' => array(
        'scene'   => '',
    ),
    '1' => array(
        'scene'   => '场景2',
    ),
    '2' => array(
        'module'  => '模块2',
        'scene'   => '场景--编辑',
    ),
);

r($tester->editScene($scene['0'])) && p('message,status') && e('编辑场景必填提示信息正确,SUCCESS');       // 编辑场景必填信息检查
r($tester->editScene($scene['1'])) && p('message,status') && e('编辑场景名称重复时提示信息正确,SUCCESS'); // 编辑场景名称重复时提示信息检查
r($tester->editScene($scene['2'])) && p('message,status') && e('场景编辑成功,SUCCESS');                   // 编辑场景

$tester->closeBrowser();
