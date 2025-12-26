#!/usr/bin/env php
<?php

/**

title=测试 storyModel::submitReview();
timeout=0
cid=18588

- 执行$storyList[28]属性status @reviewing
- 执行$storyList[30]属性status @reviewing
- 执行$storyReviewList[28] @admin|user1|user2
- 执行$story25属性status @active
- 执行$story26属性status @reviewing

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 准备基础测试数据
$story = zenData('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},0,0,18,0{100}');
$story->twins->range('``{27},30,``,28');
$story->version->range('3');
$story->status->range('active{30}');
$story->gen(30);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

// 清理旧的评审记录
$storyReview = zenData('storyreview');
$storyReview->story->range('1-30');
$storyReview->reviewer->range('admin');
$storyReview->version->range('3');
$storyReview->gen(30);

global $tester;
$tester->loadModel('story');

// 测试步骤1：正常提交评审 - 多个评审员
$storyData1 = new stdclass();
$storyData1->reviewer = array('admin', 'user1', 'user2');
$storyData1->reviewedBy = '';

$tester->story->submitReview(28, $storyData1);
$storyList = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('28,30')->fetchAll('id');
r($storyList[28]) && p('status') && e('reviewing');

// 测试步骤2：验证twins需求同时更新
r($storyList[30]) && p('status') && e('reviewing');

// 测试步骤3：验证评审员记录正确设置
$storyReviewList = $tester->story->dao->select('*')->from(TABLE_STORYREVIEW)->where('story')->in('28,30')->fetchGroup('story', 'reviewer');
r(implode('|', array_keys($storyReviewList[28]))) && p() && e('admin|user1|user2');

// 测试步骤4：测试空评审员情况
$storyData2 = new stdclass();
$storyData2->reviewer = array();
$storyData2->reviewedBy = '';

$tester->story->submitReview(25, $storyData2);
$story25 = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->eq(25)->fetch();
r($story25) && p('status') && e('active');

// 测试步骤5：测试单个评审员
$storyData3 = new stdclass();
$storyData3->reviewer = array('admin');
$storyData3->reviewedBy = '';

$tester->story->submitReview(26, $storyData3);
$story26 = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->eq(26)->fetch();
r($story26) && p('status') && e('reviewing');
