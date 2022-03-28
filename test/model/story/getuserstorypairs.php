<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getUserStoryPairs();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('story');

$skipProductIDList = array(1, 2, 3, 4, 5);
$user2Stories      = $tester->story->getUserStoryPairs('user2', '10');
$adminRequirements = $tester->story->getUserStoryPairs('admin', '50', 'requirement');
$user2Stories      = $tester->story->getUserStoryPairs('user2', '10', 'story', $skipProductIDList);
a($user2Stories);die;

$openedByUser2Stories   = $tester->story->getUserStories('user2', 'openedBy', 'id_asc', $pager, 'story', true);
$closedByTest3Stories   = $tester->story->getUserStories('test3', 'closedBy', 'id_asc', null, 'requirement', true);
$emptyReviewedByStories = $tester->story->getUserStories('', 'reviewedBy');

r(count($user2Stories))      && p() && e('10');   //获取指派给User2的需求数量，每页10条
r(count($adminRequirements)) && p() && e('50');   //获取指派给admin的用户需求，每页50条
