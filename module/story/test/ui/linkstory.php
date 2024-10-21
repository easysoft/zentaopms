#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
title=研发需求关联需求测试
timeout=0
cid=83
*/
chdir(__DIR__);
include '../lib/linkstory.ui.class.php';

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
$story->title->range('激活研发需求,激活用户需求,激活业务需求');
$story->type->range('story,requirement,epic');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(3);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-3');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求,激活用户需求,激活业务需求');
$storyspec->gen(3);

$action = zenData('action');
$action->id->range('1-4');
$action->objectType->range('product,story,story,story');
$action->objectID->range('1,1,2,3');
$action->product->range('`,1,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
$action->action->range('opened');
$action->read->range('0');
$action->vision->range('rnd');
$action->gen(4);

$relation = zenData('relation');
$relation->id->range('[]');
$relation->product->range('[]');
$relation->project->range('[]');
$relation->execution->range('[]');
$relation->AType->range('[]');
$relation->AID->range('[]');
$relation->AVersion->range('[]');
$relation->relation->range('[]');
$relation->BType->range('[]');
$relation->BID->range('[]');
$relation->BVersion->range('[]');
$relation->gen(0);

$tester = new linkStoryTester();
$tester->login();

r($tester->linkStory(1)) && p('message,status') && e('关联需求成功,SUCCESS'); // 关联需求后查看需求详情页关联的需求是否正确
$tester->closeBrowser();
