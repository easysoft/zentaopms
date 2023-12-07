#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->checkIncompatible();
timeout=0
cid=1

- 判断返回的数据是否是数组。 @1

*/

global $tester;
$tester->loadModel('extension');

$versions = array();
$versions['zentaopatch'] = '1.0';
$apiVersions = $tester->extension->checkIncompatible($versions);

r(is_array($apiVersions)) && p() && e('1'); // 判断返回的数据是否是数组。
