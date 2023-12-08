#!/usr/bin/env php
<?php
/**

title=测试 extensionModel->executeDB();
timeout=0
cid=1

- 执行code1插件的安装SQL语句。属性result @ok
- 执行code1插件的卸载SQL语句。属性result @ok

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('extension')->gen(10);

global $tester, $app;
$tester->loadModel('extension');

r($tester->extension->executeDB('code1', 'install'))   && p('result') && e('ok'); // 执行code1插件的安装SQL语句。
r($tester->extension->executeDB('code1', 'uninstall')) && p('result') && e('ok'); // 执行code1插件的卸载SQL语句。
