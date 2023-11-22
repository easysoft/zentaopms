#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('lang')->config('lang')->gen(5);
zdTable('user')->gen(5);
su('admin');

/**

title=测试 customModel->getURSRList();
timeout=0
cid=1

*/

$customTester = new customTest();
r($customTester->getURSRListTest()) && p('1:SRName,URName,system') && e('软件需求,用户需求,1');  //测试正常查询
