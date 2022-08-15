#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getRelease();
cid=1
pid=1

获取刚创建的发布 >> Version1

*/

global $tester;
$api = new apiTest();

$normalRelease = new stdclass();
$normalRelease->version   = 'Version1';
$normalRelease->desc      = '';
$normalRelease->lib       = 910;
$normalRelease->addedBy   = $tester->app->user->account;
$normalRelease->addedDate = helper::now();

$release = $api->publishLibTest($normalRelease, false);
r($api->getReleaseTest($release->lib)) && p('version') && e('Version1'); //获取刚创建的发布