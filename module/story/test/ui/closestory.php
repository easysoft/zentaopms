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
$story->module->range('0');
$story->plan->range('0');
$story->source->range('[]');
$story->title->range('激活研发需求1,激活研发需求2,激活用户需求1,激活用户需求2,激活业务需求1,激活业务需求2');
$story->type->range('story{2},requirement{2},epic{2}');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(6);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-6');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求1,激活研发需求2,激活用户需求1,激活用户需求2,激活业务需求1,激活业务需求2');
$storyspec->gen(6);

$action = zenData('action');
$action->id->range('1-7');
$action->objectType->range('product,story{6}');
$action->objectID->range('1,[1-6]');
$action->product->range('`,1,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
$action->action->range('opened');
$action->read->range('0');
$action->vision->range('rnd');
$action->gen(7);

$tester = new closeStoryTester();
$tester->login();

$closeReason = array('已完成', '不做');

r($tester->closeStory(1, $closeReason[0])) && p('message,status') && e('关闭需求成功，SUCCESS');

r($tester->batchCloseStory($closeReason[1])) && p('message,status') && e('批量关闭需求成功，SUCCESS');

$tester->closeBrowser();
