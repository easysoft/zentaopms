#!/usr/bin/env php
<?php
/**

title=测试 customModel->getModuleMenu();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(10);
zdTable('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->getModuleMenuTest('main'))    && p() && e('0'); // 获取一级菜单
r($customTester->getModuleMenuTest('product')) && p() && e('0'); // 获取产品菜单
r($customTester->getModuleMenuTest('my'))      && p() && e('0'); // 获取地盘菜单

zdTable('lang')->gen(0);
