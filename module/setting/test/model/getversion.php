#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

zdTable('config')->gen(7);

/**

title=测试 settingModel->getVersion();
timeout=0
cid=1

- 测试正常查询version @10.0

*/

$setting = new settingTest();

r($setting->getVersionTest()) && p() && e('10.0'); //测试正常查询version