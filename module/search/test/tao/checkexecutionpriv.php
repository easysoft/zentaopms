#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkExecutionPriv();
timeout=0
cid=0

- 执行searchTest模块的checkExecutionPrivTest方法，参数是array  @2
- 执行searchTest模块的checkExecutionPrivTest方法，参数是array  @0
- 执行searchTest模块的checkExecutionPrivTest方法，参数是array  @0
- 执行searchTest模块的checkExecutionPrivTest方法，参数是array  @2
- 执行searchTest模块的checkExecutionPrivTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->checkExecutionPrivTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), array(1 => 1, 2 => 2), '1,2,3')) && p() && e(2);
r($searchTest->checkExecutionPrivTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), array(4 => 1, 5 => 2), '1,2,3')) && p() && e(0);
r($searchTest->checkExecutionPrivTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), array(1 => 1, 2 => 2), '')) && p() && e(0);
r($searchTest->checkExecutionPrivTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2), 3 => (object)array('id' => 3)), array(1 => 1, 4 => 2, 2 => 3), '1,2')) && p() && e(2);
r($searchTest->checkExecutionPrivTest(array(1 => (object)array('id' => 1)), array('0' => 1), '1,2,0')) && p() && e(1);