#!/usr/bin/env php
<?php

/**
title=检查测试单下的分组视图
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/groupcase.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);
