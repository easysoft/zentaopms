#!/usr/bin/env php
<?php

/**

title=测试 storyModel->saveEstimateInfo();
cid=18583

- 测试 story的id，圆整值，平均值
 - 属性story @2
 - 属性round @2
 - 属性average @1.50
- 测试 dev1和dev2的预计
 - 第dev1条的estimate属性 @1
 - 第dev2条的estimate属性 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('storyestimate')->gen(10);

$_POST['account'][0]  = 'dev1';
$_POST['estimate'][0] = '1';
$_POST['account'][1]  = 'dev2';
$_POST['estimate'][1] = '2';
$_POST['average']     = '1.5';

global $tester;
$tester->loadModel('story');
$tester->story->saveEstimateInfo(2);
$storyInfo = $tester->story->getEstimateInfo(2);

r($storyInfo)           && p('story,round,average')         && e('2,2,1.50'); // 测试 story的id，圆整值，平均值
r($storyInfo->estimate) && p('dev1:estimate;dev2:estimate') && e('1,2');      // 测试 dev1和dev2的预计
