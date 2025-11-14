#!/usr/bin/env php
<?php

/**

title=测试 adminZen::syncPublicClasses();
timeout=0
cid=14993

- 执行$adminTest, 'syncPublicClassesTest') ? 1 : 0 @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是3)) ? 1 : 0  @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是1)) ? 1 : 0  @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是5)) ? 1 : 0  @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是0)) ? 1 : 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $config;

$adminTest = new adminZenTest();

r(method_exists($adminTest, 'syncPublicClassesTest') ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncPublicClassesTest(3)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncPublicClassesTest(1)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncPublicClassesTest(5)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncPublicClassesTest(0)) ? 1 : 0) && p() && e(1);