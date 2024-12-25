#!/usr/bin/env php
<?php

/**

title=项目发布关联和移除Bug
timeout=0
cid=73

- 项目发布关联bug
 - 测试结果 @发布关联bug成功
 - 最终测试状态 @SUCCESS
- 单个移除bug
 - 测试结果 @单个移除bug成功
 - 最终测试状态 @SUCCESS
- 移除全部bug
 - 测试结果 @移除全部bug成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/releaselinkbug.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('1');
$system->product->range('1');
$system->name->range('应用AAA');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(1);

$release = zenData('release');
$release->id->range('1');
