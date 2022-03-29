<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getTeamMembers();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('story');
$members1 = $tester->story->getTeamMembers(20, '');
$members2 = $tester->story->getTeamMembers(21, 'changed');

r() && p() && e();
