#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->review();
cid=1
pid=1

评审ID为2的需求，不传入评审意见，给出没有选择评审意见的提示 >> 必须选择评审意见
评审一个草稿的需求，传入评审意见为通过，状态变为激活 >> active
评审一个草稿的需求，传入评审意见为拒绝，状态变为关闭 >> closed

*/

$story = new storyTest();
$pass['result']   = 'pass';
$reject['result'] = 'reject';

$review1 = $story->reviewTest(2, array());
$review2 = $story->reviewTest(302, $pass);
$review3 = $story->reviewTest(304, $reject);

r($review1[0]) && p() && e('必须选择评审意见'); // 评审ID为2的需求，不传入评审意见，给出没有选择评审意见的提示
r($review2) && p('status') && e('active');      // 评审一个草稿的需求，传入评审意见为通过，状态变为激活
r($review3) && p('status') && e('closed');      // 评审一个草稿的需求，传入评审意见为拒绝，状态变为关闭
