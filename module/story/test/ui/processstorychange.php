#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
 * title=变更父需求后子需求确认变更
 * timeout=0
 * cid=83
 * - 变更父需求后检查子需求需要确认变更
 */
chdir (__DIR__);
include '../lib/processstorychange.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
