#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->unlinkStory();
cid=1
pid=1

测试normal状态的发布，正常存在的storyID值为2，可移除 >> 1
测试normal状态的发布，不存在的storyID值为100，不可移除 >> 1,2
测试normal状态的发布，不存在的storyID值为空，不可移除 >> 1,2
测试terminate状态的发布，正常存在的storyID值为2，可移除 >> 1
测试terminate状态的发布，不存在的storyID值为100，不可移除 >> 1,2
测试terminate状态的发布，不存在的storyID值为空，不可移除 >> 1,2

*/
$releaseID = array(1, 10);
$storyID = array(2, 100, 0);

$projectrelease = new projectreleaseTest();

r($projectrelease->unlinkStoryTest($releaseID[0], $storyID[0])) && p('stories') && e('1');   //测试normal状态的发布，正常存在的storyID值为2，可移除
r($projectrelease->unlinkStoryTest($releaseID[0], $storyID[1])) && p('stories') && e('1,2'); //测试normal状态的发布，不存在的storyID值为100，不可移除
r($projectrelease->unlinkStoryTest($releaseID[0], $storyID[2])) && p('stories') && e('1,2'); //测试normal状态的发布，不存在的storyID值为空，不可移除
r($projectrelease->unlinkStoryTest($releaseID[1], $storyID[0])) && p('stories') && e('1');   //测试terminate状态的发布，正常存在的storyID值为2，可移除
r($projectrelease->unlinkStoryTest($releaseID[1], $storyID[1])) && p('stories') && e('1,2'); //测试terminate状态的发布，不存在的storyID值为100，不可移除
r($projectrelease->unlinkStoryTest($releaseID[1], $storyID[2])) && p('stories') && e('1,2'); //测试terminate状态的发布，不存在的storyID值为空，不可移除
