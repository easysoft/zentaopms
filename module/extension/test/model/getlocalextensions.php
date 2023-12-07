#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(10);
/**

title=测试 extensionModel->getLocalExtensions();
timeout=0
cid=1

- 获取已安装的插件列表。
 - 第code1条的name属性 @这是插件名称1
 - 第code1条的type属性 @extension
 - 第code7条的name属性 @这是插件名称7
 - 第code7条的type属性 @extension
- 获取被禁用的插件列表。
 - 第code2条的name属性 @这是插件名称2
 - 第code2条的type属性 @extension
 - 第code8条的name属性 @这是插件名称8
 - 第code8条的type属性 @extension
- 获取已下载的插件列表。 @0
- 获取已安装或被禁用的插件列表。
 - 第code1条的name属性 @这是插件名称1
 - 第code1条的type属性 @extension
 - 第code2条的name属性 @这是插件名称2
 - 第code2条的type属性 @extension
- 获取不存在状态的插件列表。 @0

*/

global $tester;
$tester->loadModel('extension');

r($tester->extension->getLocalExtensions('installed'))             && p('code1:name,type;code7:name,type') && e('这是插件名称1,extension,这是插件名称7,extension'); // 获取已安装的插件列表。
r($tester->extension->getLocalExtensions('deactivated'))           && p('code2:name,type;code8:name,type') && e('这是插件名称2,extension,这是插件名称8,extension'); // 获取被禁用的插件列表。
r($tester->extension->getLocalExtensions('available'))             && p() && e('0');                                                                                // 获取已下载的插件列表。
r($tester->extension->getLocalExtensions('installed,deactivated')) && p('code1:name,type;code2:name,type') && e('这是插件名称1,extension,这是插件名称2,extension'); // 获取已安装或被禁用的插件列表。
r($tester->extension->getLocalExtensions('normal'))                && p() && e('0');                                                                                // 获取不存在状态的插件列表。
