#!/usr/bin/env php
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
include '../lib/assigntostory.ui.class.php';

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
$story->title->range('研发需求');
$story->type->range('story');
$story->stage->range('developing');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->gen(1);

$storyspec = zenData('storyspec');
$storyspec->story->range('1');
$storyspec->version->range('1');
$storyspec->title->range('研发需求');
$storyspec->gen(1);

$tester = new assignToStoryTester();
$tester->login();

r($tester->assignToStory()) && p('message') && e('指派需求成功');
$tester->closeBrowser();
