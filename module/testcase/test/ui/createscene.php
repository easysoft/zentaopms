#!/usr/bin/env php
<?php

/**

title=建场景
timeout=0
cid=73

- 建场景时维护模块，场景必填信息检查
 - 测试结果 @建场景必填提示信息正确
 - 最终测试状态 @SUCCESS
- 创建场景
 - 测试结果 @场景创建成功
 - 最终测试状态 @SUCCESS
- 创建场景时名称重复检查
 - 测试结果 @建场景名称重复时提示信息正确
 - 最终测试状态 @SUCCESS
- 创建场景
 - 测试结果 @场景创建成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createscene.ui.class.php';

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

$tester = new createSceneTester();
$tester->login();

//设置数据
$scene = array(
    '0' => array(
        'product' => '产品01',
        'module1' => '模块1',
        'module2' => '模块2',
        'module'  => '模块1',
        'scene'   => '',
    ),
    '1' => array(
        'product' => '产品01',
        'module'  => '模块1',
        'scene'   => '场景1',
    ),
    '2' => array(
        'product' => '产品01',
        'module'  => '模块1',
        'scene'   => '场景1',
    ),
    '3' => array(
        'product'     => '产品01',
        'module'      => '模块1',
        'parentscene' => '场景1',
        'scene'       => '场景2',
    )
);

r($tester->createScene($scene['0'])) && p('message,status') && e('建场景必填提示信息正确,SUCCESS');          // 建场景时维护模块，场景必填信息检查
r($tester->createScene($scene['1'])) && p('message,status') && e('场景创建成功,SUCCESS');                    // 创建场景
r($tester->createScene($scene['2'])) && p('message,status') && e('建场景名称重复时提示信息正确,SUCCESS');    // 创建场景时名称重复检查
r($tester->createScene($scene['3'])) && p('message,status') && e('场景创建成功,SUCCESS');                    // 创建场景

$tester->closeBrowser();
