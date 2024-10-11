#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
title=研发需求关联需求测试
timeout=0
cid=83
*/
chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
