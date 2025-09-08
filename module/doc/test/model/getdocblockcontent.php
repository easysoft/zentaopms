#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocBlockContent();
timeout=0
cid=0

- 执行docTest模块的getDocBlockContentTest方法，参数是1  @0
- 执行docTest模块的getDocBlockContentTest方法  @0
- 执行docTest模块的getDocBlockContentTest方法，参数是-1  @0
- 执行docTest模块的getDocBlockContentTest方法，参数是999  @0
- 执行$docTest, 'getDocBlockContentTest' @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

r($docTest->getDocBlockContentTest(1)) && p() && e('0');
r($docTest->getDocBlockContentTest(0)) && p() && e('0');
r($docTest->getDocBlockContentTest(-1)) && p() && e('0');
r($docTest->getDocBlockContentTest(999)) && p() && e('0');
r(method_exists($docTest, 'getDocBlockContentTest')) && p() && e('1');