#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoriesForBatchSubmitReview();
timeout=0
cid=18675

- 执行storyZenTest模块的buildStoriesForBatchSubmitReviewTest方法，参数是array 第1条的status属性 @active
- 执行storyZenTest模块的buildStoriesForBatchSubmitReviewTest方法，参数是array 属性reviewer[2] @『评审人员』不能为空。
- 执行storyZenTest模块的buildStoriesForBatchSubmitReviewTest方法，参数是array 第1条的status属性 @active
- 执行storyZenTest模块的buildStoriesForBatchSubmitReviewTest方法，参数是array 第4条的status属性 @active
- 执行storyZenTest模块的buildStoriesForBatchSubmitReviewTest方法，参数是array 属性reviewer[5] @『评审人员』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1');
$story->title->range('需求标题{1-5}');
$story->status->range('active');
$story->stage->range('wait');
$story->type->range('story');
$story->version->range('1');
$story->openedBy->range('admin');
$story->gen(5);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-5');
$storySpec->version->range('1');
$storySpec->title->range('需求标题{1-5}');
$storySpec->gen(5);

su('admin');

$storyZenTest = new storyZenTest();

r($storyZenTest->buildStoriesForBatchSubmitReviewTest(array('id' => array(1), 'reviewer' => array(array('user1', 'user2')), 'status' => 'active'))) && p('1:status') && e('active');
r($storyZenTest->buildStoriesForBatchSubmitReviewTest(array('id' => array(2), 'reviewer' => array(array()), 'status' => 'active'))) && p('reviewer[2]') && e('『评审人员』不能为空。');
global $config;
$config->story->needReview = 0;
r($storyZenTest->buildStoriesForBatchSubmitReviewTest(array('id' => array(1), 'reviewer' => array(array()), 'status' => 'active'))) && p('1:status') && e('active');
$config->story->needReview = 1;
r($storyZenTest->buildStoriesForBatchSubmitReviewTest(array('id' => array(4), 'reviewer' => array(array('user3')), 'status' => 'active'))) && p('4:status') && e('active');
r($storyZenTest->buildStoriesForBatchSubmitReviewTest(array('id' => array(5), 'reviewer' => array(array()), 'status' => 'active'))) && p('reviewer[5]') && e('『评审人员』不能为空。');