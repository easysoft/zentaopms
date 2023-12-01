#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 block 模块 model下的 create 方法
timeout=0
cid=39

- 测试产品为0时的设置菜单 @1
- 测试产品为1时的设置菜单 @1

*/

global $tester;
$tester->loadModel('qa');

r($tester->qa->setMenu(0)) && p() && e('1'); // 测试产品为0时的设置菜单
r($tester->qa->setMenu(1)) && p() && e('1'); // 测试产品为1时的设置菜单
