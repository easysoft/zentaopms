#!/usr/bin/env php
<?php
/**

title=测试 storyModel->updateStoryByReview();
timeout=0
cid=18596

- 执行storyModel模块的updateStoryByReview方法，参数是1, $oldStory, $story
 - 属性status @draft
 - 属性reviewedBy @admin,user1
- 执行storyModel模块的updateStoryByReview方法，参数是1, $oldStory, $story
 - 属性status @draft
 - 属性reviewedBy @admin
- 执行storyModel模块的updateStoryByReview方法，参数是1, $oldStory, $story
 - 属性status @active
 - 属性reviewedBy @admin,user1,user2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('story')->gen(1);
$storyView = zenData('storyreview');
$storyView->story->range('1');
$storyView->reviewer->range('admin,user1,user2');
$storyView->version->range('1');
$storyView->result->range('pass,pass,pass');
$storyView->gen(3);

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->status     = 'draft';
$story->reviewedBy = 'admin,user1';

$oldStory = new stdclass();
$oldStory->status  = 'draft';
$oldStory->version = '1';

$storyModel->app->rawModule = 'story';
$storyModel->app->user->account = 'admin';
$storyModel->config->story->superReviewers = 'admin';
$storyModel->config->story->reviewRules = 'halfpass';
r($storyModel->updateStoryByReview(1, $oldStory, $story)) && p('status;reviewedBy', ';') && e('draft;admin,user1');

$storyModel->config->story->superReviewers = '';
$story->reviewedBy = 'admin';
r($storyModel->updateStoryByReview(1, $oldStory, $story)) && p('status;reviewedBy', ';') && e('draft;admin');
$story->reviewedBy = 'admin,user1,user2';
r($storyModel->updateStoryByReview(1, $oldStory, $story)) && p('status;reviewedBy', ';') && e('active;admin,user1,user2');
