#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::responseAfterShowImport();
timeout=0
cid=0

- 执行caselibTest模块的responseAfterShowImportTest方法，参数是1, array  @1
- 执行caselibTest模块的responseAfterShowImportTest方法，参数是1, array  @1
- 执行caselibTest模块的responseAfterShowImportTest方法，参数是1, array_fill  @1
- 执行caselibTest模块的responseAfterShowImportTest方法，参数是1, array_fill  @1
- 执行caselibTest模块的responseAfterShowImportTest方法，参数是1, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

su('admin');

$caselibTest = new caselibTest();

r($caselibTest->responseAfterShowImportTest(1, array(), 0, 0, 0, 'empty_data')) && p() && e('1');
r($caselibTest->responseAfterShowImportTest(1, array(1 => (object)array('title' => 'Test Case 1'), 2 => (object)array('title' => 'Test Case 2')), 0, 0, 0, 'normal_data')) && p() && e('1');
r($caselibTest->responseAfterShowImportTest(1, array_fill(1, 150, (object)array('title' => 'Test Case')), 0, 0, 0, 'over_limit')) && p() && e('1');
r($caselibTest->responseAfterShowImportTest(1, array_fill(1, 150, (object)array('title' => 'Test Case')), 50, 1, 0, 'pagination')) && p() && e('1');
r($caselibTest->responseAfterShowImportTest(1, array(), 50, 2, 0, 'empty_pagination')) && p() && e('1');