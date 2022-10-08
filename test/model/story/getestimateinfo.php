#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getEstimateInfo();
cid=1
pid=1



*/

global $tester;
$tester->loadModel('story');
$storyInfo = $tester->story->getEstimateInfo(2);

r() && p() && e();