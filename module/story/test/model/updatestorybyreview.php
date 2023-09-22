#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

/**

title=测试 storyModel->getReviewerPairs();
cid=1
pid=1

*/

zdTable('story')->gen(1);
$storyView = zdTable('storyreview');
$storyView->story->range('1');
$storyView->reviewer->range('admin,user1,user2');
$storyView->version->range('1');
$storyView->result->range('pass,pass,pass');
$storyView->gen(3);

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->status     = 'draft';

$oldStory = new stdclass();
$oldStory->status  = 'draft';
$oldStory->version = '1';

$storyModel->app->user->account = 'admin';
$storyModel->config->story->superReviewers = 'admin';
$storyModel->config->story->reviewRules = 'halfpass';
r($storyModel->updateStoryByReview(1, $oldStory, $story)) && p('status') && e('draft');

$storyModel->config->story->superReviewers = '';
$story->reviewedBy = 'admin';
r($storyModel->updateStoryByReview(1, $oldStory, $story)) && p('status') && e('draft');
$story->reviewedBy = 'admin,user1,user2';
r($storyModel->updateStoryByReview(1, $oldStory, $story)) && p('status') && e('active');
