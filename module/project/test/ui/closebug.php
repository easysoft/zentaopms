#!/usr/bin/env php
<?php

/**

title=项目下关闭Bug操作检查
timeout=0
cid=1

- 执行tester模块的closeBug方法▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @关闭Bug成功

*/

chdir(__DIR__);
include '../lib/bug.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);
