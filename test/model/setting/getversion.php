#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->getVersion();
cid=1
pid=1

测试正常查询version >> 16.5

*/

$setting = new settingTest();

r($setting->getVersionTest()) && p() && e('16.5'); //测试正常查询version