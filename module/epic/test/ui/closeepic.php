#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=关闭业务需求测试
timeout=0
cid=90

*/
chdir (__DIR__);
include '../lib/closeepic.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
