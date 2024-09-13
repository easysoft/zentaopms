<?php
declare(strict_types=1);
/**
 *
 * title=指派给需求测试
 * timeout=0
 * cid=89
 * - 指派需求后检查指派人信息正确
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
$story->id->range('1-2');
$story->root->range('1-2');
$story->path->range('`,1,`, `,2,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->title->range('激活研发需求,草稿研发需求');
$story->type->range('story');
$story->stage->range('wait');
$story->status->range('reviewing');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->reviewedDate->range('`NULL`');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(2);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-2');
$storyspec->version->range('1');
$storyspec->title->range('激活研发需求,草稿研发需求');
$storyspec->gen(1);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-2');
$storyreview->reviewer->range('admin');
$storyreview->result->range('[]');
$storyreview->version->range('1');
$storyreview->gen(2);

$tester = new reviewStoryTester();
$tester->login();

$result = array('确认通过', '有待明确');
$status = array('激活', '草稿');

r($tester->reviewStory($result[0], $status[0])) && p('message') && e('评审研发需求成功');
r($tester->reviewStory($result[1], $status[1])) && p('message') && e('评审研发需求成功');
$tester->closeBrowser();
