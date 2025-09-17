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
include '../lib/ui/exporthtml.ui.class.php';

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
$release->product->range('1');
$release->branch->range('0');
$release->name->range('发布1');
$release->system->range('1');
$release->stories->range('[]');
$release->bugs->range('[]');
$release->desc->range('描述111');
$release->deleted->range('0');
$release->gen(1);

$tester = new exportHtmlTester();
$tester->login();

//设置导出发布数据
$release = array(
    array('filename' => ''),
    array('filename' => '导出文件1'),
    array('filename' => '导出文件2', 'exportdata' => '解决的Bug'),
);

r($tester->exportWithNoFilename($release['0'])) && p('message,status') && e('发布导出必填提示信息正确,SUCCESS'); // 发布导出时文件名必填项检查
r($tester->exportHtml($release['1']))           && p('message,status') && e('发布导出成功,SUCCESS');             // 发布导出所有数据
r($tester->exportHtml($release['2']))           && p('message,status') && e('发布导出成功,SUCCESS');             // 发布导出指定数据

$tester->closeBrowser();
