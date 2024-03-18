#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getVersions();
cid=0

- 执行story模块的getVersions方法，参数是array
 - 属性1 @3
 - 属性2 @3
 - 属性3 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(10);

global $tester;
$tester->loadModel('story');

r($tester->story->getVersions(array(1,2,3))) && p('1,2,3') && e('3,3,3');
