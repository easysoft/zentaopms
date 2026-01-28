#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doUpdateReviewer();
cid=18489

- 只传入评审人列表。 @0
- 只传入软件需求 ID。第0条的reviewer属性 @user2
- 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。属性reviewer @user2
- 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。属性reviewer @user3
- 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。属性reviewer @user4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->id->range('1-10');
$story->version->range('1');
$story->twins->range('2,1');
$story->gen(2);

$storyReview = zenData('storyreview');
$storyReview->story->range('1{2},2{2}');
$storyReview->version->range('1');
$storyReview->reviewer->range('admin,user1,admin,user1');
$storyReview->gen(4);

$story1 = $tester->loadModel('story')->fetchByID(1);
$story1->reviewer = array('user2', 'user3', 'user4');

$story = new storyModelTest();
r($story->doUpdateReviewerTest(0, (array)clone($story1))) && p() && e('0'); // 只传入评审人列表。
r($story->doUpdateReviewerTest(1, (array)clone($story1))) && p('0:reviewer') && e('user2'); // 只传入软件需求 ID。

$reviewers = $story->objectModel->dao->select('*')->from(TABLE_STORYREVIEW)->where('story')->eq('2')->fetchAll();
r($reviewers[0]) && p('reviewer') && e('user2'); // 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
r($reviewers[1]) && p('reviewer') && e('user3'); // 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
r($reviewers[2]) && p('reviewer') && e('user4'); // 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
