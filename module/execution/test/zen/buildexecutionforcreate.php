#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildExecutionForCreate();
timeout=0
cid=0

- 执行executionzenTest模块的buildExecutionForCreateTest方法  @alse
- 执行executionzenTest模块的buildExecutionForCreateTest方法  @alse
- 执行executionzenTest模块的buildExecutionForCreateTest方法  @alse
- 执行executionzenTest模块的buildExecutionForCreateTest方法  @alse
- 执行executionzenTest模块的buildExecutionForCreateTest方法  @alse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->gen(0);

su('admin');

$executionzenTest = new executionZenTest();

$_POST = array();
r($executionzenTest->buildExecutionForCreateTest()) && p() && e(false);
$_POST['project'] = 0;
r($executionzenTest->buildExecutionForCreateTest()) && p() && e(false);
$_POST['project'] = '';
r($executionzenTest->buildExecutionForCreateTest()) && p() && e(false);
$_POST['project'] = null;
r($executionzenTest->buildExecutionForCreateTest()) && p() && e(false);
$_POST['project'] = false;
r($executionzenTest->buildExecutionForCreateTest()) && p() && e(false);