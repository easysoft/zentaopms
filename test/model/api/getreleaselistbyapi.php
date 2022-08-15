#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getReleaseListByApi();
cid=1
pid=1

获取刚插入的发布信息 >> 910

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
r($api->getReleaseListByApiTest($release->lib, $release->id)) && p("lib") && e('910'); //获取刚插入的发布信息
