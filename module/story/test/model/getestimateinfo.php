#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
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