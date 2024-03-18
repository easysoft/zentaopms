#!/usr/bin/env php
<?php

/**

title=测试 storyModel->superReview();
cid=0

- 执行$reviewers第0条的reviewer属性 @user1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$storyView = zdTable('storyreview');
$storyView->story->range(1);
$storyView->reviewer->range('admin,user1');
$storyView->result->range('``,pass');
$storyView->gen(2);

global $tester;
$storyModel = $tester->loadModel('story');

$story = new stdclass();
$story->status  = 'draft';

$oldStory = new stdclass();
$oldStory->status  = 'draft';
$oldStory->version = '1';
$oldStory->twins   = '';

$storyModel->superReview(1, $oldStory, $story, 'pass');
$reviewers = $storyModel->dao->select('*')->from(TABLE_STORYREVIEW)->where('story')->eq(1)->fetchAll();
r($reviewers) && p('0:reviewer') && e('user1');
