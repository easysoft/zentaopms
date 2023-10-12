#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('storyestimate')->gen(10);

/**

title=测试 storyModel->getEstimateRounds();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
r($tester->story->getEstimateRounds(0)) && p()    && e('0');
r($tester->story->getEstimateRounds(2)) && p('1') && e('第 1 轮估算');

