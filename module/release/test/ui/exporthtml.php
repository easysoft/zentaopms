#!/usr/bin/env php
<?php

/**

title=项目发布导出HTML
timeout=0
cid=73

- 发布导出时文件名必填项检查
 - 测试结果 @发布导出必填提示信息正确
 - 最终测试状态 @SUCCESS
- 发布导出所有数据
 - 测试结果 @发布导出成功
 - 最终测试状态 @SUCCESS
- 发布导出指定数据
 - 测试结果 @发布导出成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/exporthtml.ui.class.php';

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
$system->createdBy->range('admin');
$system->gen(1);

$release = zenData('release');
$release->id->range('1');
$release->project->range('0');
