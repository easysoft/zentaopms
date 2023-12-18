#!/usr/bin/env php
<?php
/**

title=测试 installModel->getIniInfo();
timeout=0
cid=1

- 验证是否能成功获取到php.ini信息。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $app;
$tester->loadModel('install');

r(strlen($tester->install->getIniInfo()) >= 20) && p() && e(1); // 验证是否能成功获取到php.ini信息。
