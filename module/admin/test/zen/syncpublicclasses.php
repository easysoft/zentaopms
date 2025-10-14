#!/usr/bin/env php
<?php

/**

title=测试 adminZen::syncPublicClasses();
timeout=0
cid=0

- 执行adminTest模块的syncPublicClassesTest方法，参数是3  @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是1  @1
- 执行adminTest模块的syncPublicClassesTest方法  @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是10  @1
- 执行adminTest模块的syncPublicClassesTest方法，参数是-1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

r($adminTest->syncPublicClassesTest(3)) && p() && e('1');
r($adminTest->syncPublicClassesTest(1)) && p() && e('1');
r($adminTest->syncPublicClassesTest(0)) && p() && e('1');
r($adminTest->syncPublicClassesTest(10)) && p() && e('1');
r($adminTest->syncPublicClassesTest(-1)) && p() && e('1');