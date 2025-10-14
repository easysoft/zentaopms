#!/usr/bin/env php
<?php

/**

title=测试 screenZen::commonAction();
timeout=0
cid=0

- 执行screenTest模块的commonActionTest方法，参数是1  @1
- 执行screenTest模块的commonActionTest方法  @1
- 执行screenTest模块的commonActionTest方法，参数是-1  @1
- 执行screenTest模块的commonActionTest方法，参数是1, false  @1
- 执行screenTest模块的commonActionTest方法，参数是999  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

r($screenTest->commonActionTest(1)) && p() && e('1');
r($screenTest->commonActionTest(0)) && p() && e('1');
r($screenTest->commonActionTest(-1)) && p() && e('1');
r($screenTest->commonActionTest(1, false)) && p() && e('1');
r($screenTest->commonActionTest(999)) && p() && e('1');