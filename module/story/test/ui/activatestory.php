#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=激活研发需求测试
timeout=0
cid=89
- 激活关闭前是草稿状态的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 激活关闭前是激活状态求的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 激活需求成功，最终测试状态 @success
 */
chdir (__DIR__);
include '../lib/activatestory.ui.class.php';

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
$story->id->range('1-2');
$story->root->range('1-2');
$story->path->range('`,1,`, `,2,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('激活研发需求, 草稿研发需求');
$story->type->range('story');
$story->stage->range('closed');
