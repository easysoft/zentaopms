#!/usr/bin/env php
<?php
/**

title=测试 customModel->buildCustomMenuMap();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(10);
zdTable('user')->gen(5);
su('admin');

$customTester  = new customTest();
r($customTester->buildCustomMenuMapTest()) && p('main:name') && e('main'); // 获取自定义菜单

zdTable('lang')->gen(0);
