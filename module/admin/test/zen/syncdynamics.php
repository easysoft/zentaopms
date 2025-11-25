#!/usr/bin/env php
<?php

/**

title=测试 adminZen::syncDynamics();
timeout=0
cid=14991

- 执行$adminTest, 'syncDynamicsTest') ? 1 : 0 @1
- 执行adminTest模块的syncDynamicsTest方法，参数是2)) ? 1 : 0  @1
- 执行adminTest模块的syncDynamicsTest方法，参数是1)) ? 1 : 0  @1
- 执行adminTest模块的syncDynamicsTest方法，参数是5)) ? 1 : 0  @1
- 执行adminTest模块的syncDynamicsTest方法，参数是0)) ? 1 : 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $config;

$adminTest = new adminZenTest();

r(method_exists($adminTest, 'syncDynamicsTest') ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncDynamicsTest(2)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncDynamicsTest(1)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncDynamicsTest(5)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncDynamicsTest(0)) ? 1 : 0) && p() && e(1);