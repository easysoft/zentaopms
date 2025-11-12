#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=指派给需求测试
timeout=0
cid=89

*/
chdir (__DIR__);
include '../lib/ui/assigntostory.ui.class.php';

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
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(3);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-3');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求,激活用户需求,激活业务需求');
$storyspec->gen(3);

$storyreview = zenData('storyreview');
$storyreview->gen(0);

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

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$tester = new assignToStoryTester();
$tester->login();

$storyType = array('story', 'requirement', 'epic');

r($tester->assignToStory($storyType[0], 1)) && p('message') && e('指派研发需求成功');
r($tester->assignToStory($storyType[1], 2)) && p('message') && e('指派用户需求成功');
r($tester->assignToStory($storyType[2], 3)) && p('message') && e('指派业务需求成功');

r($tester->batchAssignStory($storyType[0], 1)) && p('message') && e('批量指派研发需求成功');
r($tester->batchAssignStory($storyType[1], 2)) && p('message') && e('批量指派用户需求成功');
r($tester->batchAssignStory($storyType[2], 3)) && p('message') && e('批量指派业务需求成功');

$tester->closeBrowser();
