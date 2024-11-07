#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=指派给需求测试
timeout=0
cid=89

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
$story->id->range('1-6');
$story->root->range('1-6');
$story->path->range('1-6')->prefix(',')->postfix(',');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('1-6')->prefix('草稿需求');
$story->type->range('story{3}, epic{3}');
$story->stage->range('wait');
$story->status->range('reviewing');
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
$storyspec->title->range('1-6')->prefix('草稿需求');
$storyspec->gen(6);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-6');
$storyreview->reviewer->range('admin');
$storyreview->result->range('[]');
$storyreview->version->range('1');
$storyreview->gen(6);

$tester = new reviewStoryTester();
$tester->login();

$storyType = array();
$storyType['story']       = 'story';
$storyType['requirement'] = 'requirement';
$storyType['epic']        = 'epic';

$storyID = array();
$storyID['1'] = 1;
$storyID['2'] = 2;
$storyID['3'] = 3;
$status = array();
$status['active'] = '激活';
$status['draft']  = '草稿';
$status['closed'] = '已关闭';

r($tester->reviewStory($storyType['story'], $storyID['1'], $result['pass'],    $status['active'])) && p('message') && e('评审需求成功'); //评审需求通过，研发需求状态为激活
r($tester->reviewStory($storyType['story'], $storyID['2'], $result['clarify'], $status['draft']))  && p('message') && e('评审需求成功'); //评审需求有待明确，研发需求状态为草稿
r($tester->reviewStory($storyType['story'], $storyID['3'], $result['reject'],  $status['closed'])) && p('message') && e('评审需求成功'); //评审需求拒绝，研发需求状态为已关闭

r($tester->reviewStory($storyType['epic'], $storyID['4'], $result['pass'],    $status['active'])) && p('message') && e('评审需求成功'); //评审需求通过，业务需求状态为激活
r($tester->reviewStory($storyType['epic'], $storyID['5'], $result['clarify'], $status['draft']))  && p('message') && e('评审需求成功'); //评审需求有待明确，业务需求状态为草稿
r($tester->reviewStory($storyType['epic'], $storyID['6'], $result['reject'],  $status['closed'])) && p('message') && e('评审需求成功'); //评审需求拒绝，业务需求状态为已关闭

$tester->closeBrowser();
