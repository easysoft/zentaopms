#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(10);

/**

title=测试 storyModel->getVersions();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

r($tester->story->getVersions(array(1,2,3))) && p('1,2,3') && e('3,3,3');
