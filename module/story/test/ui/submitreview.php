#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
 *
 * title=需求提交评审测试
 * timeout=0
 * cid=82
 * - 需求提交评审后检查需求状态正确
 */
chdir (__DIR__);
include '../lib/reviewstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
