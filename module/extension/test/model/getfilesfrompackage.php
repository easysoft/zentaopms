#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getFilesFromPackage();
timeout=0
cid=1

- 获取代号为code1的插件包文件列表。 @0

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getFilesFromPackage('code1'))  && p() && e(0);  // 获取代号为code1的插件包文件列表。
