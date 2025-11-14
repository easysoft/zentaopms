#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getVersions();
timeout=0
cid=18569

- 查看需求1-5的版本
 - 属性1 @3
 - 属性2 @3
 - 属性3 @3
 - 属性4 @3
 - 属性5 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('story')->gen(10);

global $tester;
$tester->loadModel('story');

r($tester->story->getVersions(array(1,2,3,4,5))) && p('1,2,3,4,5') && e('3,3,3,3,3'); // 查看需求1-5的版本