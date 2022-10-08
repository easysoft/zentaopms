#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/projectrelease.class.php';
su('admin');

/**

title=测试 projectreleaseModel->getReleasedBuilds();
cid=1
pid=1

执行projectID正常存在 >> 2
执行projectID不存在 >> 0
执行projectID为空 >> 1

*/

$projectID = array(131, 1000, '');

$projectrelease = new projectreleaseTest();

r($projectrelease->getReleasedBuildsTest($projectID[0])) && p('1') && e('2');  //执行projectID正常存在
r($projectrelease->getReleasedBuildsTest($projectID[1])) && p()    && e('0');  //执行projectID不存在
r($projectrelease->getReleasedBuildsTest($projectID[2])) && p()    && e('1');  //执行projectID为空