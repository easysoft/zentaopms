#!/usr/bin/env php
<?php
/**

title=测试 installModel->getIniInfo();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $app;
$tester->loadModel('install');

r(substr($tester->install->getIniInfo(), 0, 9)) && p() && e('System =>'); // 获取中文授权内容。
