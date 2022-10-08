#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
$db->switchDB();
su('admin');

/**

title=测试 settingModel->setSN();
cid=1
pid=1

测试正常设置SN >> 1

*/

$setting = new settingTest();

r($setting->setSNTest()) && p() && e('1'); //测试正常设置SN

$db->restoreDB();