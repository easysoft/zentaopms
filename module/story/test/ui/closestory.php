#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=关闭研发需求测试
timeout=0
cid=88
- 关闭没有父需求的研发需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 关闭需求成功，最终测试状态 @success
- 批量关闭需求后检查信息正确
 -  属性module @story
 -  属性method @view
- 批量关闭需求成功，最终测试状态 @success
 */
chdir (__DIR__);
include '../lib/closestory.ui.class.php';

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
$tester = new closeStoryTester();
$tester->login();

$closeReason = array('已完成', '不做');

r($tester->closeStory(1, $closeReason[0])) && p('message,status') && e('关闭需求成功，SUCCESS');

r($tester->batchCloseStory($closeReason[1])) && p('message,status') && e('批量关闭需求成功，SUCCESS');

$tester->closeBrowser();
