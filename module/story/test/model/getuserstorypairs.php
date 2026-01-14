#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getUserStoryPairs();
timeout=0
cid=18567

- 获取指派给 user2 的需求数量，每页10条 @10
- 获取指派给 user2 的用户需求，每页20条 @20
- 获取指派给 user2 的所有需求总数 @40
- 获取指派给 user2 的、不在产品5里的用户需求 @30
- 获取指派给 user2 的、不在产品4,5里的用户需求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');
$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->gen(3);

$story = zenData('story');
$story->type->range('requirement{60},story{40}');
$story->assignedTo->range('admin{10},user1{10},user2{80}');
$story->deleted->range('0');
$story->status->range('active,draft');
$story->product->range('1{10},2{5},3{5},4{70},5{10}');
$story->gen(100);

$product = zenData('product');
$product->gen(5);

global $tester;
$tester->loadModel('story');

$skipProductIDList        = array(5);
$user2Stories             = $tester->story->getUserStoryPairs('user2', 10);
$adminRequirements        = $tester->story->getUserStoryPairs('user2', 20, 'requirement');
$allUser2Stories          = $tester->story->getUserStoryPairs('user2', 100, 'story');
$user2StoriesSkipProducts = $tester->story->getUserStoryPairs('user2', 100, 'story', $skipProductIDList);

r(count($user2Stories))             && p() && e('10'); //获取指派给 user2 的需求数量，每页10条
r(count($adminRequirements))        && p() && e('20'); //获取指派给 user2 的用户需求，每页20条
r(count($allUser2Stories))          && p() && e('40'); //获取指派给 user2 的所有需求总数
r(count($user2StoriesSkipProducts)) && p() && e('30'); //获取指派给 user2 的、不在产品5里的用户需求

$user2StoriesSkipProducts = $tester->story->getUserStoryPairs('user2', 100, 'story', array(4,5));
r(count($user2StoriesSkipProducts)) && p() && e('0'); //获取指派给 user2 的、不在产品4,5里的用户需求