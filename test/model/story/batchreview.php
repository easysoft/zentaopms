#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchReview();
cid=1
pid=1

批量评审传入三个ID，查看被评审的需求数量 >> 2
批量评审需求，查看被评审后的需求状态、阶段等信息 >> active,projected,done
批量评审传入三个ID，查看被评审的需求数量 >> 2
批量评审需求，查看被评审后的需求状态、阶段等信息 >> closed,closed,bydesign
批量评审传入三个ID，查看被评审的需求数量 >> 2
批量评审需求，查看被评审后的需求状态、阶段等信息 >> active,projected,cancel

*/

$story = new storyTest();

$storyIdList1 = array(302, 304, '');
$storyIdList2 = array(308, 310, 0);
$storyIdList3 = array(314, 316, 2);

$review1 = $story->batchReviewTest($storyIdList1, 'pass', '');
$review2 = $story->batchReviewTest($storyIdList2, 'reject', 'done'); 
$review3 = $story->batchReviewTest($storyIdList3, 'pass', '');

r(count($review1)) && p()                                && e('2');                       // 批量评审传入三个ID，查看被评审的需求数量
r($review1)        && p('302:status,stage,closedReason') && e('active,projected,done');   // 批量评审需求，查看被评审后的需求状态、阶段等信息
r(count($review2)) && p()                                && e('2');                       // 批量评审传入三个ID，查看被评审的需求数量
r($review2)        && p('308:status,stage,closedReason') && e('closed,closed,bydesign');  // 批量评审需求，查看被评审后的需求状态、阶段等信息
r(count($review3)) && p()                                && e('2');                       // 批量评审传入三个ID，查看被评审的需求数量
r($review3)        && p('314:status,stage,closedReason') && e('active,projected,cancel'); // 批量评审需求，查看被评审后的需求状态、阶段等信息
