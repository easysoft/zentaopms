#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
 * title=变更父需求后子需求确认变更
 * timeout=0
 * cid=83
 * - 变更父需求后检查子需求需要确认变更
 */
chdir (__DIR__);
include '../lib/processstorychange.ui.class.php';

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
$story->parent->range('0,1');
$story->isParent->range('1,0');
$story->root->range('1');
$story->path->range('`,1,`,`,1,2,`');
$story->grade->range('1-2');
$story->product->range('1');
$story->module->range('0');
$story->title->range('父研发需求,子研发需求');
$story->type->range('story');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->parentVersion->range('0,1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(2);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-2');
$storyspec->version->range('1');
$storyspec->title->range('父研发需求,子研发需求');
$storyspec->gen(2);

$action = zenData('action');
$action->id->range('1-3');
$action->objectType->range('product,story{2}');
$action->objectID->range('1');
$action->product->range('`,1,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
