#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('storyestimate')->gen(10);

/**

title=测试 storyModel->getEstimateInfo();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$storyInfo = $tester->story->getEstimateInfo(2);

r($storyInfo) && p('round,average') && e('1,1.5');
