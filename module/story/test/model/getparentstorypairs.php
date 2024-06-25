#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getParentStoryPairs();
timeout=0
cid=0

- 获取ID为3的父需求的标题第3条的keys属性 @用户需求9
- 获取符合条件的需求数 @3
- 测试附加的需求ID1，需求1以数字1结尾第0条的keys属性 @软件需求2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->gen(3);

$story = zenData('story');
$story->type->range('requirement{60},story{40}');
$story->assignedTo->range('admin{10},user1{10},user2{80}');
$story->deleted->range('0');
$story->stage->range('wait,closed');
$story->status->range('active,draft');
$story->product->range('1{10},2{5},3{5},4{70},5{10}');
$story->parent->range('0,1');
$story->plan->range('0,1');
$story->version->range('1');
$story->grade->range('1{10},2{10}');
$story->gen(100);

zenData('storyspec')->gen(100);
zenData('storygrade')->gen(6);

$product = zenData('product');
$product->gen(5);

global $tester;
$tester->loadModel('story');
$stories = $tester->story->getParentStoryPairs(1);

r($stories) && p('3:keys') && e('用户需求9'); // 获取ID为3的父需求的标题

array_pop($stories); /* remove empty item at array top. */
r(count($stories)) && p() && e(3);  // 获取符合条件的需求数

$storiesWithAppended = $tester->story->getParentStoryPairs(5, 2);
r($storiesWithAppended) && p('0:keys') && e('软件需求2'); // 测试附加的需求ID1，需求1以数字1结尾