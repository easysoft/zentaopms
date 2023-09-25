#!/usr/bin/env php
<?php
/**
title=测试 storyModel->getParentStoryPairs();
cid=1
pid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$user = zdTable('user');
$user->account->range('admin,user1,user2');
$user->gen(3);

$story = zdTable('story');
$story->type->range('requirement{60},story{40}');
$story->assignedTo->range('admin{10},user1{10},user2{80}');
$story->deleted->range('0');
$story->stage->range('wait,closed');
$story->status->range('active,draft');
$story->product->range('1{10},2{5},3{5},4{70},5{10}');
$story->parent->range('0,1');
$story->plan->range('0,1');
$story->gen(100);

$product = zdTable('product');
$product->gen(5);

global $tester;
$tester->loadModel('story');
$stories = $tester->story->getParentStoryPairs(5);

r($stories) && p('0:0') && e('~~'); // 第一个元素是 0 => ''

array_pop($stories); /* remove empty item at array top. */
r(count($stories)) && p() && e(5);  // 获取符合条件的需求数