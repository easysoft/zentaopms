#!/usr/bin/env php
<?php

/**

title=项目下导出Bug操作检查
timeout=0
cid=1

- 按照默认设置导出
 - 最终测试状态 @SUCCESS
 - 测试结果 @导出Bug成功
- 导出xml选中记录
 - 最终测试状态 @SUCCESS
 - 测试结果 @导出Bug成功
- 导出html全部记录
 - 最终测试状态 @SUCCESS
 - 测试结果 @导出Bug成功

*/

chdir(__DIR__);
include '../lib/bug.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);
