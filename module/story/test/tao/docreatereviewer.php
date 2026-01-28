#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doCreateReviewer();
cid=18617

- 不传入任何数据。 @0
- 只传入评审人列表。 @0
- 只传入软件需求 ID。 @0
- 传入软件需求 ID 和 评审人列表，查看storyreview表记录的数量。 @2
- 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
 - 属性story @1
 - 属性reviewer @admin
- 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
 - 属性story @1
 - 属性reviewer @test1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('storyreview')->gen(1);

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->doCreateReviewer(0, array()))  && p() && e('0'); // 不传入任何数据。
r($storyModel->doCreateReviewer(0, array(1))) && p() && e('0'); // 只传入评审人列表。
r($storyModel->doCreateReviewer(1, array()))  && p() && e('0'); // 只传入软件需求 ID。

$story = new storyTaoTest();
$requirementResult = $story->doCreateReviewerTest(1, array('admin', 'test1'));

r(count($requirementResult)) && p()                 && e('2');       // 传入软件需求 ID 和 评审人列表，查看storyreview表记录的数量。
r($requirementResult[0])     && p('story,reviewer') && e('1,admin'); // 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
r($requirementResult[1])     && p('story,reviewer') && e('1,test1'); // 传入软件需求 ID 和 评审人列表，查看storyreview表记录的关系。
