#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);
zenData('product')->gen(10);

/**

title=测试 systemModel::initSystem();
timeout=0
cid=18741

- 传入字符串11测试是否初始化失败 @0
- 传入字符串22测试是否初始化失败 @0
- 传入字符串33测试是否初始化失败 @0
- 传入数字44测试是否初始化失败 @0
- 传入字符串55测试是否初始化失败 @0

 */
global $tester;

$system = $tester->loadModel('system');

r($system->initSystem('11')) && p('dao.error') && e('0');
r($system->initSystem('22')) && p('dao.error') && e('0');
r($system->initSystem('33')) && p('dao.error') && e('0');
r($system->initSystem(44))   && p('dao.error') && e('0');
r($system->initSystem('55')) && p('dao.error') && e('0');