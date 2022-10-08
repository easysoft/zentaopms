#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 extensionModel->getLocalExtensions();
cid=1
pid=1

获取已经安装的插件数量 >> 5
获取已经销毁的插件数量 >> 5
获取代号为code1的插件详细信息 >> 1,这是插件名称1,extension,installed
获取代号为code2的插件详细信息 >> 2,这是插件名称2,extension,deactivated

*/

global $tester;
$tester->loadModel('extension');
$installedExt   = $tester->extension->getLocalExtensions('installed');
$deactivatedExt = $tester->extension->getLocalExtensions('deactivated');

r(count($installedExt))   && p()                            && e('5'); // 获取已经安装的插件数量 
r(count($deactivatedExt)) && p()                            && e('5'); // 获取已经销毁的插件数量
r($installedExt)          && p('code1:id,name,type,status') && e('1,这是插件名称1,extension,installed');  // 获取代号为code1的插件详细信息
r($deactivatedExt)        && p('code2:id,name,type,status') && e('2,这是插件名称2,extension,deactivated');  // 获取代号为code2的插件详细信息