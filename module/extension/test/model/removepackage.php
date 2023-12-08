#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->removePackage();
timeout=0
cid=1

- 删除安装的code1插件文件检查返回值是否是数组。 @1
- 删除安装的code1插件文件并检查有没有错误信息。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(10);

global $tester, $app;
$tester->loadModel('extension');

$app->setAppRoot('', dirname(__FILE__, 5));
$result = $tester->extension->removePackage('code1');
r(is_array($result))  && p() && e(1);   // 删除安装的code1插件文件检查返回值是否是数组。
r($result)  && p() && e(0);             // 删除安装的code1插件文件并检查有没有错误信息。
