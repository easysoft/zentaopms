#!/usr/bin/env php
<?php

/**

title=项目版本详情
timeout=0
cid=73

- 项目版本详情检查
 - 测试结果 @项目版本详情查看成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/view.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
