#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoriesForBatchSubmitReview();
timeout=0
cid=1

- 执行story模块的batchSubmitReview方法，参数是$stories1  @1
- 执行$story1Result属性status @reviewing
- 执行$story2Result属性status @draft
- 执行story模块的batchSubmitReview方法，参数是$stories3  @1
- 执行story模块的batchSubmitReview方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

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
$story->type->range('story');
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

/* 步骤1：批量提交评审 - 包含有评审员和空评审员的需求 */
$stories1 = array();
$story1 = new stdclass();
$story1->reviewer = array('admin', 'user1');
$story1->reviewedBy = '';
$stories1[1] = $story1;
$story2 = new stdclass();
$story2->reviewer = array();
$story2->reviewedBy = '';
$stories1[2] = $story2;

r($tester->story->batchSubmitReview($stories1)) && p() && e('1');

/* 步骤2：有评审员的需求状态变为reviewing */
$story1Result = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(1)->fetch();
r($story1Result) && p('status') && e('reviewing');

/* 步骤3：空评审员提交后保持原有状态 */
$story2Result = $tester->dao->select('*')->from(TABLE_STORY)->where('id')->eq(2)->fetch();
r($story2Result) && p('status') && e('draft');

/* 步骤4：单个需求提交评审 */
$stories3 = array();
$story3 = new stdclass();
$story3->reviewer = array('admin');
$story3->reviewedBy = '';
$stories3[3] = $story3;

r($tester->story->batchSubmitReview($stories3)) && p() && e('1');

/* 步骤5：提交空数组 */
r($tester->story->batchSubmitReview(array())) && p() && e('1');