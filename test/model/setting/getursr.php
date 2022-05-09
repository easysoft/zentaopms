#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/setting.class.php';
su('admin');

/**

title=测试 settingModel->getURSR();
cid=1
pid=1

测试正常查询URSR >> 2

*/

$setting = new settingTest();

r($setting->getURSRTest()) && p() && e('2'); //测试正常查询URSR