#!/usr/bin/env php
<?php
/**

title=测试 customModel->setMenuByConfig();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$customTester  = new customTest();
r($customTester->setMenuByConfigTest('main'))    && p() && e('0'); // 设置一级菜单
r($customTester->setMenuByConfigTest('product')) && p() && e('0'); // 设置产品菜单
r($customTester->setMenuByConfigTest('my'))      && p() && e('0'); // 设置地盘菜单

zenData('lang')->gen(0);
