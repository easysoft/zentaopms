#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
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
$story->version->range('3');
$story->gen(30);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

$storyReview = zdTable('storyreview');
$storyReview->story->range('1-30');
$storyReview->reviewer->range('admin');
$storyReview->version->range('3');
$storyReview->gen(30);

/**

title=测试 storyModel->recallChange();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

$tester->story->recallChange(28);
$storyList       = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('28,30')->fetchAll('id');
$storySpecList   = $tester->story->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->in('28,30')->fetchGroup('story', 'version');
$storyReviewList = $tester->story->dao->select('*')->from(TABLE_STORYREVIEW)->where('story')->in('28,30')->fetchGroup('story', 'version');

r($storyList[28]) && p('title,status,version') && e('用户需求版本二89,active,2');
r($storyList[30]) && p('title,status,version') && e('用户需求版本二89,active,2');
r((int)isset($storySpecList[28][3]))   && p() && e('0');
r((int)isset($storySpecList[30][3]))   && p() && e('0');
r((int)isset($storyReviewList[28][3])) && p() && e('0');
r((int)isset($storyReviewList[30][3])) && p() && e('0');
