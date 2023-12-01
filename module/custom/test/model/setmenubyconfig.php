#!/usr/bin/env php
<?php
/**

title=测试 customModel->setMenuByConfig();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(10);
zdTable('user')->gen(5);
su('admin');

$customTester  = new customTest();
r($customTester->setMenuByConfigTest('main'))    && p() && e('0'); // 设置一级菜单
r($customTester->setMenuByConfigTest('product')) && p() && e('0'); // 设置产品菜单
r($customTester->setMenuByConfigTest('my'))      && p() && e('0'); // 设置地盘菜单

zdTable('lang')->gen(0);
