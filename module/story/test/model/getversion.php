#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getVersion();
timeout=0
cid=18568

- 查看需求1的版本 @3
- 查看需求2的版本 @3
- 查看需求3的版本 @3
- 查看需求4的版本 @3
- 查看需求5的版本 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('story')->gen(10);

global $tester;
$tester->loadModel('story');

r($tester->story->getVersion(1)) && p() && e('3'); // 查看需求1的版本
r($tester->story->getVersion(2)) && p() && e('3'); // 查看需求2的版本
r($tester->story->getVersion(3)) && p() && e('3'); // 查看需求3的版本
r($tester->story->getVersion(4)) && p() && e('3'); // 查看需求4的版本
r($tester->story->getVersion(5)) && p() && e('3'); // 查看需求5的版本