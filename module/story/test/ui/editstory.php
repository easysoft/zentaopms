#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=编辑研发需求测试
timeout=0
cid=80

- 编辑需求的来源后的链接检查
 - 属性module @story
 - 属性method @view
- 编辑研发需求
- 成功 最终测试状态 @SUCCESS

- 批量编辑研发需求
 - 成功 最终测试状态 @SUCCESS
*/
chdir(__DIR__);
include '../lib/editstory.ui.class.php';

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
$story->plan->range('0');
$story->source->range('[]');
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

$tester = new editStoryTester();
$tester->login();

$storyFrom = '客户';

r($tester->editStory($storyFrom)) && p('module,method')  && e('story,view'); // 编辑需求后跳转页面检查
r($tester->editStory($storyFrom)) && p('message,status') && e('编辑需求成功,SUCCESS'); // 编辑需求成功

r($tester->batchEditStory($storyFrom)) && p('message,status')  && e('批量编辑研发需求成功,SUCCESS'); // 编辑需求后跳转页面检查
$tester->closeBrowser();
