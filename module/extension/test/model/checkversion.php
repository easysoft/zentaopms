#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->checkVersion();
timeout=0
cid=1

- 检查当前版本是否在版本号为all中。 @1
- 检查当前版本是否在版本号为999中。 @0
- 检查当前版本是否在版本号为999,当前版本中。 @1

*/

global $tester, $config;
$tester->loadModel('extension');

r($tester->extension->checkVersion('all'))  && p() && e(1);                     // 检查当前版本是否在版本号为all中。
r($tester->extension->checkVersion('9999')) && p() && e(0);                     // 检查当前版本是否在版本号为999中。
r($tester->extension->checkVersion('9999,' . $config->version)) && p() && e(1); // 检查当前版本是否在版本号为999,当前版本中。
