#!/usr/bin/env php
<?php

/**
title=关联项目
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/project.ui.class.php';

$product = zenData('product');
$product->id->range('1-2');
$product->program->range('0');
$product->name->range('产品1,产品2');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(2);
