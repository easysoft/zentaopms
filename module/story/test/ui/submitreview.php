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
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(1);

$storyspec = zenData('storyspec');
$storyspec->story->range('1');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求,草稿研发需求');
$storyspec->gen(1);

$action = zenData('action');
$action->id->range('1-2');
$action->objectType->range('product,story{1}');
$action->objectID->range('1');
$action->product->range('`,1,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
$action->action->range('opened');
$action->read->range('0');
$action->vision->range('rnd');
$action->gen(2);

$tester = new reviewStoryTester();
$tester->login();

r($tester->submitReview()) && p('message,status') && e('提交评审成功,SUCCESS');
$tester->closeBrowser();
