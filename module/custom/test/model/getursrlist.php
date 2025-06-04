#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);
su('admin');

/**

title=测试 customModel->getURSRList();
timeout=0
cid=1

- 测试正常查询
 - 第1条的SRName属性 @软件需求
 - 第1条的URName属性 @用户需求
 - 第1条的system属性 @1

*/

$customTester = new customTest();
r($customTester->getURSRListTest()) && p('1:SRName,URName,system') && e('软件需求,用户需求,1');  //测试正常查询