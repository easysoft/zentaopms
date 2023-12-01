#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

/**

title=测试 settingModel->setSN();
timeout=0
cid=1

- 测试正常设置SN @1

*/

$setting = new settingTest();

r($setting->setSNTest()) && p() && e('1'); //测试正常设置SN