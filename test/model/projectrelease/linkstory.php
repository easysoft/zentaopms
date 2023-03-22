#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->linkStory();
cid=1
pid=1

测试normal状态的发布，releaseID正常存在 >> ,1,2
测试terminate状态的发布，releaseID正常存在 >> ,1,2

*/
$releaseID = array(1, 10);

$projectrelease = new projectreleaseTest();

r($projectrelease->linkStoryTest($releaseID[0])) && p('stories') && e(',1,2'); //测试normal状态的发布，releaseID正常存在
r($projectrelease->linkStoryTest($releaseID[1])) && p('stories') && e(',1,2'); //测试terminate状态的发布，releaseID正常存在
