#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->config('extension')->gen(20);
/**

title=测试 extensionModel->getDependsExtension();
timeout=0
cid=1

- 获取依赖code1插件的插件列表。
 - 第0条的name属性 @这是插件名称6
 - 第0条的code属性 @code6
 - 第0条的type属性 @extension
 - 第1条的name属性 @这是插件名称12
 - 第1条的code属性 @code12
 - 第1条的type属性 @extension
- 获取依赖code2插件的插件列表。
 - 第0条的name属性 @这是插件名称6
 - 第0条的code属性 @code6
 - 第0条的type属性 @extension
 - 第1条的name属性 @这是插件名称12
 - 第1条的code属性 @code12
 - 第1条的type属性 @extension
- 获取依赖code4插件的插件列表。 @0

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getDependsExtension('code1'))  && p('0:name,code,type;1:name,code,type') && e('这是插件名称6,code6,extension,这是插件名称12,code12,extension'); // 获取依赖code1插件的插件列表。
r($tester->extension->getDependsExtension('code3'))  && p('0:name,code,type;1:name,code,type') && e('这是插件名称6,code6,extension,这是插件名称12,code12,extension'); // 获取依赖code2插件的插件列表。
r($tester->extension->getDependsExtension('code4'))  && p() && e('0');                                                                                                // 获取依赖code4插件的插件列表。
