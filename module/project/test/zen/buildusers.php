#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildUsers();
timeout=0
cid=17933

- 执行projectTest模块的buildUsersTest方法 属性pairsCount @20
- 执行projectTest模块的buildUsersTest方法 属性hasAdmin @1
- 执行projectTest模块的buildUsersTest方法 属性adminRealname @admin
- 执行projectTest模块的buildUsersTest方法 属性hasAdminObject @1
- 执行projectTest模块的buildUsersTest方法 属性adminAccount @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

zenData('user')->gen(20);

su('admin');

$projectTest = new projectzenTest();

r($projectTest->buildUsersTest()) && p('pairsCount') && e('20');
r($projectTest->buildUsersTest()) && p('hasAdmin') && e('1');
r($projectTest->buildUsersTest()) && p('adminRealname') && e('admin');
r($projectTest->buildUsersTest()) && p('hasAdminObject') && e('1');
r($projectTest->buildUsersTest()) && p('adminAccount') && e('admin');