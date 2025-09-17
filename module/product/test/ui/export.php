#!/usr/bin/env php
<?php
/**
title=导出产品
timeout=0
cid=0

- 正常导出
 - 测试结果 @导出产品成功
 - 最终测试状态 @SUCCESS
*/
chdir(__DIR__);
include '../lib/ui/all.ui.class.php';

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->status->range('normal{2},closed{1}');
$product->type->range('normal');
$product->gen(3);

$tester = new allTester();
$tester->login();

r($tester->export()) && p('message,status') && e('导出产品成功,SUCCESS');//正常导出
$tester->closeBrowser();