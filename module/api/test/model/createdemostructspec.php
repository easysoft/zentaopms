#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoStructSpec();
timeout=0
cid=15098

- 执行apiTest模块的createDemoStructSpecTest方法，参数是'v1', 'admin'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'v1', 'user'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'v1', 'tester'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'v1', 'guest'  @1
- 执行apiTest模块的createDemoStructSpecTest方法，参数是'v1', 'manager'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('apistruct_spec')->loadYaml('apistruct_spec', false, 2)->gen(0);

su('admin');

$apiTest = new apiModelTest();

r($apiTest->createDemoStructSpecTest('v1', 'admin')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('v1', 'user')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('v1', 'tester')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('v1', 'guest')) && p() && e('1');
r($apiTest->createDemoStructSpecTest('v1', 'manager')) && p() && e('1');