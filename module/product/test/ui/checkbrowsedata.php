#!/usr/bin/env php
<?php

/**
title=检查产品-业务需求/用户需求/研发需求各tab数据
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/browse.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);
