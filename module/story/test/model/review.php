#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},`-1`,0,18,0{100}');
$story->twins->range('``{27},30,``,28');
$story->version->range('1');
$story->gen(30);

$storyReview = zdTable('storyreview');
$storyReview->story->range('1-30');
$storyReview->reviewer->range('admin');
$storyReview->version->range('1');
$storyReview->gen(20);

/**

title=测试 storyModel->review();
cid=1
pid=1

*/

$story = new storyTest();

$storyData = new stdclass();
$storyData->result       = 'pass';
$storyData->assignedTo   = 'admin';
$storyData->closedReason = '';
$storyData->pri          = '2';
r($story->reviewTest(1, $storyData)) && p('status') && e('active');      // 评审一个草稿的需求，传入评审意见为通过，状态变为激活

$storyData = new stdclass();
$storyData->result       = 'reject';
$storyData->assignedTo   = 'admin';
$storyData->closedReason = '';
$storyData->pri          = '2';
r($story->reviewTest(5, $storyData)) && p('status') && e('closed');      // 评审一个草稿的需求，传入评审意见为通过，状态变为激活
