#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=激活研发需求测试
timeout=0
cid=89

*/
chdir (__DIR__);
include '../lib/ui/activatestory.ui.class.php';

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
$story->title->range('激活研发需求, 草稿研发需求, 激活用户需求, 草稿用户需求, 激活业务需求, 草稿业务需求');
$story->type->range('story{2}, requirement{2}, epic{2}');
$story->stage->range('closed');
$story->status->range('closed');
$story->openedBy->range('admin');
$story->version->range('1');
$story->gen(6);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-6');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求, 草稿研发需求, 激活用户需求, 草稿用户需求, 激活业务需求, 草稿业务需求');
$storyspec->gen(6);

$action = zenData('action');
$action->id->range('1-6');
$action->objectType->range('story');
$action->objectID->range('1-6');
$action->product->range('`,1`');
$action->actor->range('admin');
$action->action->range('closed');
$action->date->range('(-2D)-(-D):60m')->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$action->extra->range('Done|active, Done|draft');
$action->gen(6);

$storyreview = zenData('storyreview')->gen(0);

$tester = new activateStoryTester();
$tester->login();

$stutus = array('激活', '草稿');

r($tester->activateStory('story', 1, $stutus[0])) && p('message') && e('激活研发需求成功');
r($tester->activateStory('story', 2, $stutus[1])) && p('message') && e('激活研发需求成功');

r($tester->activateStory('requirement', 3, $stutus[0])) && p('message') && e('激活用户需求成功');
r($tester->activateStory('requirement', 4, $stutus[1])) && p('message') && e('激活用户需求成功');

r($tester->activateStory('epic', 5, $stutus[0])) && p('message') && e('激活业务需求成功');
r($tester->activateStory('epic', 6, $stutus[1])) && p('message') && e('激活业务需求成功');

$tester->closeBrowser();
