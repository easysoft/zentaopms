#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=激活研发需求测试
timeout=0
cid=89

*/
chdir (__DIR__);
include '../lib/activatestory.ui.class.php';

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
$story->id->range('1-2');
$story->root->range('1-2');
$story->path->range('`,1,`, `,2,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('激活研发需求, 草稿研发需求');
$story->type->range('story');
$story->stage->range('closed');
$story->status->range('closed');
$story->openedBy->range('admin');
$story->version->range('1');
$story->gen(2);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-2');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求, 草稿研发需求');
$storyspec->gen(2);

$action = zenData('action');
$action->id->range('1-2');
$action->objectType->range('story');
$action->objectID->range('1-2');
$action->product->range('`,1`');
$action->actor->range('admin');
$action->action->range('closed');
$action->date->range('(-2D)-(-D):60m')->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$action->extra->range('Done|active, Done|draft');
$action->gen(2);

$tester = new activateStoryTester();
$tester->login();

$stutus = array('激活', '草稿');
r($tester->activateStory(1, $stutus[0])) && p('message') && e('激活需求成功');
r($tester->activateStory(2, $stutus[1])) && p('message') && e('激活需求成功');

$tester->closeBrowser();
