#!/usr/bin/env php
<?php

/**
title=概况页面
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/view.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);
