#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 extensionModel->parseExtensionCFG();
timeout=0
cid=1

- 执行 @1
- 获取代号为code1的插件包配置信息
 - 属性name @~~
 - 属性code @~~
 - 属性version @~~
 - 属性author @~~
 - 属性desc @~~

*/

global $tester;
$tester->loadModel('extension');

r(1) && p() && e('1');
r($tester->extension->parseExtensionCFG('code1'))  && p('name,code,version,author,desc') && e('~~,~~,~~,~~,~~');   // 获取代号为code1的插件包配置信息
