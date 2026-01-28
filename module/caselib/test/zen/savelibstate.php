#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::saveLibState();
timeout=0
cid=15560

- 执行caselibTest模块的saveLibStateTest方法，参数是1, array  @0
- 执行caselibTest模块的saveLibStateTest方法，参数是5, array  @5
- 执行caselibTest模块的saveLibStateTest方法，参数是0, array  @3
- 执行caselibTest模块的saveLibStateTest方法，参数是0, array  @7
- 执行caselibTest模块的saveLibStateTest方法，参数是0, array  @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$caselibTest = new caselibZenTest();

// 测试步骤1：空libraries数组情况
r($caselibTest->saveLibStateTest(1, array())) && p() && e('0');

// 测试步骤2：libID大于0且libraries不为空
r($caselibTest->saveLibStateTest(5, array(1 => 'lib1', 2 => 'lib2', 5 => 'lib5'))) && p() && e('5');

// 测试步骤3：libID为0且有cookie.lastCaseLib
global $tester;
$tester->app->cookie->lastCaseLib = 3;
r($caselibTest->saveLibStateTest(0, array(1 => 'lib1', 3 => 'lib3', 5 => 'lib5'))) && p() && e('3');

// 测试步骤4：libID为0且无session.caseLib
$tester->app->session->set('caseLib', null);
$tester->app->cookie->lastCaseLib = null;
r($caselibTest->saveLibStateTest(0, array(7 => 'lib7', 8 => 'lib8', 9 => 'lib9'))) && p() && e('7');

// 测试步骤5：session.caseLib不在libraries中，重置为libraries第一个key
$tester->app->session->set('caseLib', 999);
r($caselibTest->saveLibStateTest(0, array(10 => 'lib10', 11 => 'lib11'))) && p() && e('10');