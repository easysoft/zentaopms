#!/usr/bin/env php
<?php

/**
title=测试单关联用例
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/linkcase.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);
