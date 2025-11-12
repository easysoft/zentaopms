#!/usr/bin/env php
<?php

/**

title=测试 bugZen::initBug();
timeout=0
cid=0

- 步骤1:测试传入空字段数组初始化bug对象,验证默认值 @0
- 步骤2:测试传入空字段数组初始化bug对象,验证默认类型 @codeerror
- 步骤3:测试传入productID字段初始化bug对象 @10
- 步骤4:测试传入多个字段初始化bug对象 @5,3,2

- 步骤5:测试传入title和severity字段初始化bug对象 @Test Bug,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$bugTest = new bugZenTest();

r($bugTest->initBugTest(array())->projectID) && p() && e('0'); // 步骤1:测试传入空字段数组初始化bug对象,验证默认值
r($bugTest->initBugTest(array())->type) && p() && e('codeerror'); // 步骤2:测试传入空字段数组初始化bug对象,验证默认类型
r($bugTest->initBugTest(array('productID' => 10))->productID) && p() && e('10'); // 步骤3:测试传入productID字段初始化bug对象
r($bugTest->initBugTest(array('projectID' => 5, 'moduleID' => 3, 'executionID' => 2))->projectID . ',' . $bugTest->initBugTest(array('projectID' => 5, 'moduleID' => 3, 'executionID' => 2))->moduleID . ',' . $bugTest->initBugTest(array('projectID' => 5, 'moduleID' => 3, 'executionID' => 2))->executionID) && p() && e('5,3,2'); // 步骤4:测试传入多个字段初始化bug对象
r($bugTest->initBugTest(array('title' => 'Test Bug', 'severity' => 1))->title . ',' . $bugTest->initBugTest(array('title' => 'Test Bug', 'severity' => 1))->severity) && p() && e('Test Bug,1'); // 步骤5:测试传入title和severity字段初始化bug对象