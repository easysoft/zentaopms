#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getPackageFile();
timeout=0
cid=1

- 获取代号为code1的插件包地址。 @/var/web/ztpms/tmp/extension/code1.zip
- 获取代号为zentaopatch的插件包地址。 @/var/web/ztpms/tmp/extension/zentaopatch.zip

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getPackageFile('code1'))        && p() && e('/var/web/ztpms/tmp/extension/code1.zip');         // 获取代号为code1的插件包地址。
r($tester->extension->getPackageFile('zentaopatch'))  && p() && e('/var/web/ztpms/tmp/extension/zentaopatch.zip');   // 获取代号为zentaopatch的插件包地址。
