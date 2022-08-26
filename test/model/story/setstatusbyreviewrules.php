#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->setStatusByReviewRules();
cid=1
pid=1

传入两个通过一个拒绝，查看返回结果 >> 0
传入全部通过，查看返回结果 >> active
传入两个拒绝一个通过，查看返回结果 >> 0
传入三个拒绝，查看返回结果 >> closed

*/

global $tester;
$tester->loadModel('story');
$reviewList1 = array('pass', 'pass', 'reject');
$reviewList2 = array('pass', 'pass', 'pass');
$reviewList3 = array('pass', 'reject', 'reject');
$reviewList4 = array('reject', 'reject', 'reject');

$result1 = $tester->story->setStatusByReviewRules($reviewList1);
$result2 = $tester->story->setStatusByReviewRules($reviewList2);
$result3 = $tester->story->setStatusByReviewRules($reviewList3);
$result4 = $tester->story->setStatusByReviewRules($reviewList4);

r($result1) && p() && e('0');      // 传入两个通过一个拒绝，查看返回结果
r($result2) && p() && e('active'); // 传入全部通过，查看返回结果
r($result3) && p() && e('0');      // 传入两个拒绝一个通过，查看返回结果
r($result4) && p() && e('closed'); // 传入三个拒绝，查看返回结果