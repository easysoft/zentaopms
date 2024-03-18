#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getEstimateRounds();
cid=0

- 执行story模块的getEstimateRounds方法  @0
- 执行story模块的getEstimateRounds方法，参数是2 属性1 @第 1 轮估算

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('storyestimate')->gen(10);

global $tester;
$tester->loadModel('story');
r($tester->story->getEstimateRounds(0)) && p()    && e('0');
r($tester->story->getEstimateRounds(2)) && p('1') && e('第 1 轮估算');
