#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/export.ui.class.php';

/**

title=开源版m=story&f=export测试
timeout=0
cid=1

- 全部记录导出流程测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @全部记录导出流程测试成功
- 选中记录导出流程测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @选中记录导出流程测试成功

*/

// 基本数据准备：创建产品与若干需求（含规格）用于导出测试
$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->shadow->range('0');
$product->gen(1);

// 创建10条未关闭需求及其规格，供“全部/选中”两类导出测试
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1');
$story->module->range('0');
$story->title->range('1-10')->prefix('需求');
$story->type->range('story');
$story->status->range('active');
$story->stage->range('projected');
$story->assignedTo->range('[]');
$story->version->range('1');
$story->gen(10);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-10');
$storySpec->version->range('1');
$storySpec->title->range('1-10');
$storySpec->spec->range('1-10')->prefix('软件需求描述');
$storySpec->verify->range('1-10')->prefix('需求验收');
$storySpec->gen(10);

$tester = new exportTester();

r($tester->testExportAll())      && p('status,message') && e('SUCCESS,全部记录导出流程测试成功'); // 全部记录导出流程测试
r($tester->testExportSelected()) && p('status,message') && e('SUCCESS,选中记录导出流程测试成功'); // 选中记录导出流程测试

$tester->closeBrowser();
