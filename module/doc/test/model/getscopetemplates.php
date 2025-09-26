#!/usr/bin/env php
<?php

/**

title=测试 docModel::getScopeTemplates();
timeout=0
cid=0

- 测试步骤1：获取空范围列表的模板 @0
- 测试步骤2：获取单个范围的模板数组长度 @1
- 测试步骤3：获取多个范围的模板数组长度 @2
- 测试步骤4：获取不存在范围的模板数组长度 @1
- 测试步骤5：获取多个范围的模板数组长度 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$docTester = new docTest();

r($docTester->getScopeTemplatesTest(array())) && p() && e('0'); // 测试步骤1：获取空范围列表的模板
r(count($docTester->getScopeTemplatesTest(array(1)))) && p() && e('1'); // 测试步骤2：获取单个范围的模板数组长度
r(count($docTester->getScopeTemplatesTest(array(1, 2)))) && p() && e('2'); // 测试步骤3：获取多个范围的模板数组长度
r(count($docTester->getScopeTemplatesTest(array(999)))) && p() && e('1'); // 测试步骤4：获取不存在范围的模板数组长度
r(count($docTester->getScopeTemplatesTest(array(1, 2, 3)))) && p() && e('3'); // 测试步骤5：获取多个范围的模板数组长度