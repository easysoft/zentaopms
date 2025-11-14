#!/usr/bin/env php
<?php

/**

title=测试 storyModel->superReview();
timeout=0
cid=18589

- 查看评审人详情
 - 第0条的story属性 @1
 - 第0条的version属性 @1
 - 第0条的reviewer属性 @user1
 - 第0条的result属性 @pass
- 检查评审日期 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$storyView = zenData('storyreview');
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
r($reviewers) && p('0:story,version,reviewer,result') && e('1,1,user1,pass'); // 查看评审人详情
r($reviewers[0]->reviewDate == date('Y-m-d 00:00:00')) && p() && e('1');      // 检查评审日期