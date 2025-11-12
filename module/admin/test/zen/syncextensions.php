#!/usr/bin/env php
<?php

/**

title=测试 adminZen::syncExtensions();
timeout=0
cid=0

- 执行$adminTest, 'syncExtensionsTest') ? 1 : 0 @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'plugin', 5)) ? 1 : 0  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'patch', 5)) ? 1 : 0  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'plugin', 3)) ? 1 : 0  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'plugin', 0)) ? 1 : 0  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'patch', 10)) ? 1 : 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $config;

$adminTest = new adminZenTest();

r(method_exists($adminTest, 'syncExtensionsTest') ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncExtensionsTest('plugin', 5)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncExtensionsTest('patch', 5)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncExtensionsTest('plugin', 3)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncExtensionsTest('plugin', 0)) ? 1 : 0) && p() && e(1);
r(is_bool($adminTest->syncExtensionsTest('patch', 10)) ? 1 : 0) && p() && e(1);