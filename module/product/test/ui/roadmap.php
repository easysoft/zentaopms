#!/usr/bin/env php
<?php

/**

title=检查产品路线图
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/roadmap.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);
