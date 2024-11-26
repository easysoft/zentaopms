#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=关闭业务需求测试
timeout=0
cid=90

*/
chdir (__DIR__);
include '../lib/closeepic.ui.class.php';

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
$story->id->range('1-6');
$story->root->range('1-6');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->source->range('[]');
$story->title->range('激活研发需求1,激活研发需求2,激活用户需求1,激活用户需求2,激活业务需求1,激活业务需求2');
$story->type->range('story{2},requirement{2},epic{2}');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
