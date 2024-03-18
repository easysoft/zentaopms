#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getEstimateInfo();
cid=0

- 执行$storyInfo
 - 属性round @1
 - 属性average @1.5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('storyestimate')->gen(10);

global $tester;
$tester->loadModel('story');
$storyInfo = $tester->story->getEstimateInfo(2);

r($storyInfo) && p('round,average') && e('1,1.5');
