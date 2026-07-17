#!/usr/bin/env php
<?php

/**

title=测试 storyModel::batchSubmitReview();
timeout=0
cid=1

- 执行story模块的batchSubmitReview方法，参数是$stories1  @1
- 执行$story1Result属性status @reviewing
- 执行story模块的batchSubmitReview方法，参数是$stories2  @1
- 执行$story3Result属性status @draft
- 执行story模块的batchSubmitReview方法，参数是$stories3  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->gen(1);

$story = zenData('story');
$story->product->range('1');
$story->version->range('3');
$story->status->range('draft{5}');
$story->gen(5);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-5{3}');
$storySpec->version->range('1-3');
$storySpec->gen(15);

$storyReview = zenData('storyreview');
$storyReview->story->range('1-5');
$storyReview->reviewer->range('admin');
$storyReview->version->range('3');
$storyReview->gen(5);

global $tester;
$tester->loadModel('story');

$stories1 = array();
$story1 = new stdclass();
$story1->reviewer = array('admin', 'user1');
$story1->reviewedBy = '';
$stories1[1] = $story1;
$story2 = new stdclass();
$story2->reviewer = array('admin');
$story2->reviewedBy = '';
$stories1[2] = $story2;

r($tester->story->batchSubmitReview($stories1)) && p() && e('1');

$story1Result = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(1)->fetch();
r($story1Result) && p('status') && e('reviewing');

$stories2 = array();
$story3 = new stdclass();
$story3->reviewer = array();
$story3->reviewedBy = '';
$stories2[3] = $story3;

r($tester->story->batchSubmitReview($stories2)) && p() && e('1');

$story3Result = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(3)->fetch();
r($story3Result) && p('status') && e('draft');

$stories3 = array();
$story4 = new stdclass();
$story4->reviewer = array('admin', 'user2');
$story4->reviewedBy = '';
$stories3[4] = $story4;
$story5 = new stdclass();
$story5->reviewer = array('user1');
$story5->reviewedBy = '';
$stories3[5] = $story5;

r($tester->story->batchSubmitReview($stories3)) && p() && e('1');