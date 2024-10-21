#!/usr/bin/env php
<?php

/**

title=创建数据结构测试
timeout=0
cid=0

- 创建数据结构
 - 测试结果 @创建数据结构成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createstruct.ui.class.php';

$doclib = zenData('doclib');
$doclib->id->range('1-2');
$doclib->type->range('api');
$doclib->product->range('0');
$doclib->name->range('独立接口库1,独立接口库2');
$doclib->acl->range('open');
$doclib->order->range('0');
$doclib->gen(2);

$tester = new createDocTester();
$tester->login();

$dataStruct = new stdClass();
$dataStruct->name = '数据结构A';

r($tester->createStruct($dataStruct)) && p('message,status') && e('创建数据结构成功,SUCCESS'); //创建数据结构