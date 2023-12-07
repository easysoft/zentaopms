#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getInfoFromPackage();
timeout=0
cid=1

- 获取插件1的详细信息
 - 属性name @code1
 - 属性code @code1
 - 属性version @unknown
 - 属性author @unknown
 - 属性desc @code1

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getInfoFromPackage('code1'))  && p('name,code,version,author,desc') && e('code1,code1,unknown,unknown,code1');   // 获取插件1的详细信息
