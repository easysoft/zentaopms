#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->erasePackage();
timeout=0
cid=1

- 清除安装的code1插件包检查返回值是否是数组。 @1
- 清除安装的code1插件包并检查有没有错误信息。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(10);

global $tester;
$tester->loadModel('extension');

$result = $tester->extension->erasePackage('code1');
r(is_array($result))  && p() && e(1);   // 清除安装的code1插件包检查返回值是否是数组。
r($result)  && p() && e(0);             // 清除安装的code1插件包并检查有没有错误信息。
