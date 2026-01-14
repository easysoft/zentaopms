#!/usr/bin/env php
<?php

/**

title=测试 caselibModel::setLibMenu();
timeout=0
cid=15536

- 执行caselibTest模块的setLibMenuTest方法，参数是array  @1
- 执行caselibTest模块的setLibMenuTest方法，参数是array  @1
- 执行caselibTest模块的setLibMenuTest方法，参数是array  @1
- 执行caselibTest模块的setLibMenuTest方法，参数是array  @1
- 执行caselibTest模块的setLibMenuTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// Initialize test environment
global $tester;
if(!isset($tester->app->user)) $tester->app->user = new stdClass();
$tester->app->user->account = 'admin';
if(!isset($tester->session)) $tester->session = new stdClass();

$caselibTest = new caselibModelTest();

r($caselibTest->setLibMenuTest(array(1 => '用例库1', 2 => '用例库2'), 1)) && p() && e('1');
r($caselibTest->setLibMenuTest(array(), 1)) && p() && e('1');
r($caselibTest->setLibMenuTest(array(), 999)) && p() && e('1');
r($caselibTest->setLibMenuTest(array(1 => '用例库1', 2 => '用例库2'), 999)) && p() && e('1');
r($caselibTest->setLibMenuTest(array(), 0)) && p() && e('1');