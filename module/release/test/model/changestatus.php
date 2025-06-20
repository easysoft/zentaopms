#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->changeStatus();
timeout=0
cid=1

- 将发布的状态由正常改为停止维护
 - 第0条的old属性 @normal
 - 第0条的new属性 @terminate
- 将发布的状态由停止维护改为正常
 - 第0条的old属性 @terminate
 - 第0条的new属性 @normal

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$release = zenData('release')->loadYaml('release');
$release->status->range('normal,terminate');
$release->gen(5);

zenData('user')->gen(5);
su('admin');

$releases = array(1, 2);
$status   = array('normal','terminate');

$releaseTester = new releaseTest();
r($releaseTester->changeStatusTest($releases[0], $status[1])) && p('0:old,new') && e('normal,terminate'); // 将发布的状态由正常改为停止维护
r($releaseTester->changeStatusTest($releases[1], $status[0])) && p('0:old,new') && e('terminate,normal'); // 将发布的状态由停止维护改为正常