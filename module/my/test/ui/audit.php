#!/usr/bin/env php
<?php

/**
title=检查地盘审批数据/在审批列表中进行评审
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/audit.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-11');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-11');
$story->path->range('1-11');
$story->grade->range('1');
$story->product->range('1');
$story->version->range('1');
$story->title->range('业需01,业需02,业需03,业需04,用需01,用需02,用需03,用需04,用需05,研需01,研需02');
$story->type->range('epic{4},requirement{5},story{2}');
$story->status->range('reviewing');
$story->openedBy->range('admin');
$story->assignedTo->range('admin');
$story->reviewedBy->range('{11}');
$story->deleted->range('0');
$story->gen(11);
$tester->closeBrowser();
