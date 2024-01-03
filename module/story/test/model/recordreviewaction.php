#!/usr/bin/env php
<?php
/**

title=测试 storyModel->recordReviewAction();
cid=1

- 执行story模块的recordReviewActionTest方法，参数是$storyData 属性action @reviewed
- 执行story模块的recordReviewActionTest方法，参数是$storyData 属性action @reviewpassed
- 执行story模块的recordReviewActionTest方法，参数是$storyData 属性action @reviewrejected
- 执行story模块的recordReviewActionTest方法，参数是$storyData 属性action @reviewclarified
- 执行story模块的recordReviewActionTest方法，参数是$storyData 属性action @reviewreverted
- 执行story模块的recordReviewActionTest方法，参数是$storyData 属性action @reviewed

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('product')->gen(1);

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
$story->version->range('1');
$story->gen(30);

$storyReview = zdTable('storyreview');
$storyReview->story->range('1-30');
$storyReview->reviewer->range('admin');
$storyReview->version->range('1');
$storyReview->gen(20);

$story = new storyTest();

$storyData = new stdclass();
$storyData->id           = '1';
$storyData->result       = 'pass';
$storyData->assignedTo   = 'admin';
$storyData->closedReason = '';
$storyData->pri          = '2';
r($story->recordReviewActionTest($storyData)) && p('action') && e('reviewed');

$storyData->finalResult = 'pass';
r($story->recordReviewActionTest($storyData)) && p('action') && e('reviewpassed');
$storyData->finalResult = 'reject';
r($story->recordReviewActionTest($storyData)) && p('action') && e('reviewrejected');
$storyData->finalResult = 'clarify';
r($story->recordReviewActionTest($storyData)) && p('action') && e('reviewclarified');
$storyData->finalResult = 'revert';
r($story->recordReviewActionTest($storyData)) && p('action') && e('reviewreverted');


$story->objectModel->app->user->account = 'admin';
$story->objectModel->app->rawModule = 'story';
$story->objectModel->app->rawMethod = 'review';
$story->objectModel->config->story->superReviewers = 'admin';
r($story->recordReviewActionTest($storyData)) && p('action') && e('reviewed');
