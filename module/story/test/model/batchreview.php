#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchReview();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

$story = zdTable('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->childStories->range('0,8,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},`-1`,0,18,0{100}');
$story->twins->range('``{27},30,``,28');
$story->status->range('reviewing');
$story->version->range('1');
$story->reviewedBy->range('``');
$story->gen(30);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-30');
$storySpec->version->range('1');
$storySpec->gen(30);

$storyReview = zdTable('storyreview');
$storyReview->story->range('1-30');
$storyReview->reviewer->range('admin');
$storyReview->version->range('1');
$storyReview->gen(30);

su('admin');

$story = new storyTest();

$storyIdList1 = array(1, 28);
$storyIdList2 = array(2);

$review1    = $story->batchReviewTest($storyIdList1, 'pass');
$twin       = $story->objectModel->fetchByID(30);
$review2    = $story->batchReviewTest($storyIdList2, 'reject', 'done');
$reviewList = $story->objectModel->dao->select('*')->from(TABLE_STORYREVIEW)->fetchAll('story');

r($review1[1]) && p('status') && e('active');
r($twin)       && p('status') && e('active');
r($review2[2]) && p('status') && e('closed');
r($reviewList[1])  && p('reviewer,result') && e('admin,pass');
r($reviewList[2])  && p('reviewer,result') && e('admin,reject');
r($reviewList[28]) && p('reviewer,result') && e('admin,pass');
r($reviewList[30]) && p('reviewer,result') && e('admin,pass');
