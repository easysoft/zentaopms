#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getVersion();
cid=0

- 执行story模块的getVersion方法，参数是2  @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(10);

global $tester;
$tester->loadModel('story');

r($tester->story->getVersion(2)) && p() && e('3');
