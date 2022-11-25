#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getUserStoryPairs();
cid=1
pid=1

获取指派给User2的需求数量，每页10条 >> 10
获取指派给admin的用户需求，每页50条 >> 50
获取指派给User2的所有需求总数 >> 100
获取指派给admin的、不在产品91/92/93里的用户需求，每页50条 >> 97

*/

global $tester;
$tester->loadModel('story');

$skipProductIDList        = array(91, 92, 93);
$user2Stories             = $tester->story->getUserStoryPairs('user2', '10');
$adminRequirements        = $tester->story->getUserStoryPairs('admin', '50', 'requirement');
$allUser2Stories          = $tester->story->getUserStoryPairs('user2', '0', 'story');
$user2StoriesSkipProducts = $tester->story->getUserStoryPairs('user2', '0', 'story', $skipProductIDList);

r(count($user2Stories))             && p() && e('10');   //获取指派给User2的需求数量，每页10条
r(count($adminRequirements))        && p() && e('50');   //获取指派给admin的用户需求，每页50条
r(count($allUser2Stories))          && p() && e('100');  //获取指派给User2的所有需求总数
r(count($user2StoriesSkipProducts)) && p() && e('97');   //获取指派给admin的、不在产品91/92/93里的用户需求，每页50条