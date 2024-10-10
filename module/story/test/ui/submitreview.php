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
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$story = zenData('story');
$story->id->range('1');
$story->root->range('1');
$story->path->range('`,1,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('草稿研发需求');
$story->type->range('story');
$story->stage->range('wait');
$story->status->range('draft');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
