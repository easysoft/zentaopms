#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getParentStoryPairs();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('story');
$stories = $tester->story->getParentStoryPairs(91);
//a($stories);die;

r() && p() && e();