#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
title= 业务需求细分用户需求测试
timeout=0
cid=80

*/
chdir(__DIR__);
include '../lib/batchcreatur.ui.class.php';
include 'page/batchcreate.php';

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
$story->id->range('1-3');
$story->root->range('1-3');
$story->path->range('`,1,`, `,2,`, `,3,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('评审中业务需求,已关闭业务需求,激活业务需求');
$story->type->range('epic');
$story->stage->range('wait');
$story->status->range('reviewing,closed,active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('admin,[]{2}');
