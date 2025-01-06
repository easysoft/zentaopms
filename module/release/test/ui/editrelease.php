#!/usr/bin/env php
<?php

/**

title=编辑发布
timeout=0
cid=73

- 发布名称置空保存，检查提示信息
 - 测试结果 @编辑发布表单页必填提示信息正确
 - 最终测试状态 @SUCCESS
- 编辑发布，修改应用
 - 测试结果 @编辑发布成功
 - 最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为已发布、计划日期、发布日期
 - 测试结果 @编辑发布成功
 - 最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为停止维护
 - 测试结果 @编辑发布成功
 - 最终测试状态 @SUCCESS
- 编辑发布，修改名称、状态改为停止维护
 - 测试结果 @编辑发布成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editrelease.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('1,2');
$system->product->range('1');
$system->name->range('应用AAA, 应用BBB');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(2);

$release = zenData('release');
$release->id->range('1');
$release->project->range('1');
$release->product->range('1');
$release->branch->range('0');
$release->name->range('release-1');
$release->system->range('1');
$release->status->range('wait');
$release->stories->range('[]');
$release->bugs->range('[]');
$release->desc->range('描述111');
$release->deleted->range('0');
$release->gen(1);

$tester = new editReleaseTester();
