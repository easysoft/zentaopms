#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->saveExtension();
timeout=0
cid=1

- 测试保存插件代号为code1的插件信息到数据库。 @1
- 测试保存插件代号为code1的补丁信息到数据库。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(0);

global $tester;
$tester->loadModel('extension');

r($tester->extension->saveExtension('code1', 'extension')) && p() && e(1); // 测试保存插件代号为code1的插件信息到数据库。
r($tester->extension->saveExtension('code1', 'patch'))     && p() && e(1); // 测试保存插件代号为code1的补丁信息到数据库。
