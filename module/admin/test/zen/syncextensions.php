#!/usr/bin/env php
<?php

/**

title=测试 adminZen::syncExtensions();
timeout=0
cid=0

- 执行adminTest模块的syncExtensionsTest方法，参数是'plugin', 5  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'patch', 5  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'plugin', 10  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'patch', 1  @1
- 执行adminTest模块的syncExtensionsTest方法，参数是'plugin', 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

r($adminTest->syncExtensionsTest('plugin', 5)) && p() && e('1');
r($adminTest->syncExtensionsTest('patch', 5)) && p() && e('1');
r($adminTest->syncExtensionsTest('plugin', 10)) && p() && e('1');
r($adminTest->syncExtensionsTest('patch', 1)) && p() && e('1');
r($adminTest->syncExtensionsTest('plugin', 0)) && p() && e('1');