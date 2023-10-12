#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('storyestimate')->gen(10);

/**

title=测试 storyModel->saveEstimateInfo();
cid=1
pid=1

*/

$_POST['account'][0]  = 'dev1';
$_POST['estimate'][0] = '1';
$_POST['account'][1]  = 'dev2';
$_POST['estimate'][1] = '2';
$_POST['average']     = '1.5';

global $tester;
$tester->loadModel('story');
$tester->story->saveEstimateInfo(2);
$storyInfo = $tester->story->getEstimateInfo(2);

r($storyInfo) && p('round,average') && e('2,1.5');
