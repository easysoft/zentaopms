#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(10);
/**

title=测试 extensionModel->getInfoFromDB();
timeout=0
cid=1

- 获取插件1的详细信息
 - 属性id @1
 - 属性name @这是插件名称1
 - 属性code @code1
 - 属性type @extension
 - 属性status @installed
- 获取插件2的详细信息
 - 属性id @2
 - 属性name @这是插件名称2
 - 属性code @code2
 - 属性type @extension
 - 属性status @deactivated
- 获取插件99的详细信息 @0

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getInfoFromDB('code1'))  && p('id,name,code,type,status') && e('1,这是插件名称1,code1,extension,installed');   // 获取插件1的详细信息
r($tester->extension->getInfoFromDB('code2'))  && p('id,name,code,type,status') && e('2,这是插件名称2,code2,extension,deactivated'); // 获取插件2的详细信息
r($tester->extension->getInfoFromDB('code99')) && p() && e('0');                                                                     // 获取插件99的详细信息
