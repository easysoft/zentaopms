#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=业务需求细分用户需求测试
timeout=0
cid=79

-评审中业务需求，不打印细分按钮
 -最终测试状态 @SUCCESS
 -测试结果 @细分按钮高亮正确
-已关闭业务需求，不打印细分按钮
 -最终测试状态 @SUCCESS
 -测试结果 @细分按钮高亮正确
-激活业务需求，打印细分按钮
 -最终测试状态 @SUCCESS
 -测试结果 @批量创建需求页面名称为空提示正确
-激活业务需求，打印细分按钮
 -最终测试状态 @SUCCESS
 -测试结果 @拆分业务需求成功

*/
chdir(__DIR__);
include '../lib/ui/batchcreatur.ui.class.php';
include '../../../requirement/test/ui/page/batchcreate.php';
include '../../../requirement/test/ui/page/view.php';

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
$story->title->range('评审中业务需求,已关闭业务需求,激活业务需求');
$story->type->range('epic');
$story->stage->range('wait');
$story->status->range('reviewing,closed,active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('admin,[]{2}');
$story->closedBy->range('[]');
$story->closedReason->range('[], 2024-12-10 14:58:34,  []');
$story->gen(3);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-3');
$storyspec->version->range('1');
$storyspec->title->range('评审中业务需求,已关闭业务需求,激活业务需求');
$storyspec->gen(3);

$storyreview = zenData('storyreview');
$storyreview->gen(0);

$project = zenData('project');
$project->gen(0);

$projectproduct = zenData('projectproduct');
$projectproduct->gen(0);

$projectstory = zenData('projectstory');
$projectstory->gen(0);

$task = zenData('task')->gen(0);

$case = zenData('case')->gen(0);

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

$tester = new createChildStoryTester();
$tester->login();

$storys = array();
$storys['null']  = '';
$storys['child'] = '子用户需求';

r($tester->checkDisplay('评审中业务需求')) && p('message,status') && e('细分按钮高亮正确, SUCCESS');
r($tester->checkDisplay('已关闭业务需求')) && p('message,status') && e('细分按钮高亮正确, SUCCESS');

r($tester->batchCreateDefault('激活业务需求', $storys['null']))  && p('message,status') && e('批量创建需求页面名称为空提示正确, SUCCESS');
r($tester->batchCreateDefault('激活业务需求', $storys['child'])) && p('message,status') && e('拆分业务需求成功, SUCCESS');
$tester->closeBrowser();
