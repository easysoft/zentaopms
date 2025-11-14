#!/usr/bin/env php
<?php

/**

title=测试 storyModel->recallReview();
timeout=0
cid=18577

- 需求28状态属性status @draft
- 需求29状态属性status @draft
- 需求30状态属性status @draft
- 撤回后，需求28版本3的评审记录不存在 @0
- 撤回后，需求29版本3的评审记录不存在 @0
- 撤回后，需求30版本3的评审记录不存在 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zenData('story');
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

$storySpec = zenData('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

$storyReview = zenData('storyreview');
$storyReview->story->range('1-30');
$storyReview->reviewer->range('admin');
$storyReview->version->range('3');
$storyReview->gen(30);

global $tester;
$tester->loadModel('story');

$tester->story->recallReview(28);
$tester->story->recallReview(29);
$tester->story->recallReview(30);
$storyList       = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('28,29,30')->fetchAll('id');
$storyReviewList = $tester->story->dao->select('*')->from(TABLE_STORYREVIEW)->where('story')->in('28,29,30')->fetchGroup('story', 'version');

r($storyList[28]) && p('status') && e('draft'); // 需求28状态
r($storyList[29]) && p('status') && e('draft'); // 需求29状态
r($storyList[30]) && p('status') && e('draft'); // 需求30状态
r((int)isset($storyReviewList[28][3])) && p() && e('0'); // 撤回后，需求28版本3的评审记录不存在
r((int)isset($storyReviewList[29][3])) && p() && e('0'); // 撤回后，需求29版本3的评审记录不存在
r((int)isset($storyReviewList[30][3])) && p() && e('0'); // 撤回后，需求30版本3的评审记录不存在