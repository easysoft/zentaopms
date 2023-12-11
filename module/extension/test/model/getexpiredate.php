#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->getExpireDate();
timeout=0
cid=1

- 获取代号为code1的插件包安装SQL文件。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('extension');

$extension = new stdclass();
$extension->code    = 'code1';
$extension->version = 'version1';

r($tester->extension->getExpireDate($extension)) && p() && e(0); // 获取代号为code1的插件包的过期时间。
