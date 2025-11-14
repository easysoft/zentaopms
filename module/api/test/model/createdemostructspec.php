#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoStructSpec();
timeout=0
cid=15098

- 执行apiTest模块的createDemoStructSpecTest方法，参数是'16.0', 'admin'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'16.0', 'user'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'16.0', 'tester'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'16.0', 'guest'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'16.0', 'manager'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

zenData('apistruct_spec')->loadYaml('apistruct_spec', false, 2)->gen(0);

su('admin');

$apiTest = new apiTest();

r($apiTest->createDemoStructSpecTest('16.0', 'admin')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('16.0', 'user')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('16.0', 'tester')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('16.0', 'guest')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('16.0', 'manager')) && p() && e('1');