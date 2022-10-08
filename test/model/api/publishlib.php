#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->publishLib();
cid=1
pid=1

没有版本名的发布 >> 『版本』不能为空。
正常的发布 >> Version1

*/

global $tester;
$api = new apiTest();

$emptyBuildRelease = new stdclass();
$emptyBuildRelease->version   = '';
$emptyBuildRelease->desc      = '';
$emptyBuildRelease->lib       = 910;
$emptyBuildRelease->addedBy   = $tester->app->user->account;
$emptyBuildRelease->addedDate = helper::now();

$normalRelease = new stdclass();
$normalRelease->version   = 'Version1';
$normalRelease->desc      = '';
$normalRelease->lib       = 910;
$normalRelease->addedBy   = $tester->app->user->account;
$normalRelease->addedDate = helper::now();

r($api->publishLibTest($emptyBuildRelease)) && p('version:0') && e('『版本』不能为空。');  //没有版本名的发布
r($api->publishLibTest($normalRelease)) && p('version') && e('Version1');                               //正常的发布
