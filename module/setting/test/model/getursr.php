#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

zdTable('config')->gen(7);

/**

title=测试 settingModel->getURSR();
timeout=0
cid=1

- 测试正常查询URSR @2

*/

$setting = new settingTest();

r($setting->getURSRTest()) && p() && e('2'); //测试正常查询URSR