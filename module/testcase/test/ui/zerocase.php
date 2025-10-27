#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=零用例需求列表
timeout=0
cid=1

- 验证验证零用例需求列表
 - 测试结果 @验证零用例需求列表成功
 - 最终测试状态 @SUCCESS

*/
include '../lib/ui/testcase.ui.class.php';
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
$story->title->range('激活研发需求,激活用户需求,激活业务需求');
$story->type->range('story,requirement,epic');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(2);

$tester = new testcase();

$url = array(
    'productID' => 1,
    'branch'    => 0,
    'orderBy'   => 'id_desc',
    'objectID'  => 0
);

r($tester->zeroCase($url)) && p('message,status') && e('验证零用例需求列表成功,SUCCESS'); //验证验证零用例需求列表
$tester->closeBrowser();