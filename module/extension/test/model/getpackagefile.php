#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getPackageFile();
timeout=0
cid=1

- 获取代号为code1的插件包地址。 @/apps/zentao/tmp/extension/code1.zip
- 获取代号为zentaopatch的插件包地址。 @/apps/zentao/tmp/extension/zentaopatch.zip


*/

global $tester;
$tester->loadModel('extension');
$tester->extension->app->tmpRoot = '/apps/zentao/tmp/';

r($tester->extension->getPackageFile('code1'))        && p() && e('/apps/zentao/tmp/extension/code1.zip');         // 获取代号为code1的插件包地址。
r($tester->extension->getPackageFile('zentaopatch'))  && p() && e('/apps/zentao/tmp/extension/zentaopatch.zip');   // 获取代号为zentaopatch的插件包地址。
