#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getInfoFromDB();
cid=1
pid=1

获取已安装的插件1的详细信息 >> 1,这是插件名称1,code1,extension,installed
获取已安装的插件2的详细信息 >> 2,这是插件名称2,code2,extension,deactivated
获取已安装的插件3的详细信息 >> 3,这是插件名称3,code3,extension,installed
获取已安装的插件4的详细信息 >> 4,这是插件名称4,code4,extension,deactivated

*/

global $tester;
$tester->loadModel('extension');

$ext1 = $tester->extension->getInfoFromDB('code1');
$ext2 = $tester->extension->getInfoFromDB('code2');
$ext3 = $tester->extension->getInfoFromDB('code3');
$ext4 = $tester->extension->getInfoFromDB('code4');

r($ext1) && p('id,name,code,type,status') && e('1,这是插件名称1,code1,extension,installed');   // 获取已安装的插件1的详细信息
r($ext2) && p('id,name,code,type,status') && e('2,这是插件名称2,code2,extension,deactivated'); // 获取已安装的插件2的详细信息 
r($ext3) && p('id,name,code,type,status') && e('3,这是插件名称3,code3,extension,installed');   // 获取已安装的插件3的详细信息
r($ext4) && p('id,name,code,type,status') && e('4,这是插件名称4,code4,extension,deactivated'); // 获取已安装的插件4的详细信息