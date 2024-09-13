<?php
declare(strict_types=1);
/**
 *
 * title=指派给需求测试
 * timeout=0
 * cid=89
 * - 指派需求后检查指派人信息正确
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
$product->createdBy->range('admin');
