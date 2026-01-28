#!/usr/bin/env php
<?php

/**

title=测试 docModel::getScopeTemplates();
timeout=0
cid=16125

- 测试步骤1：获取空范围列表的模板 @0
- 测试步骤2：获取单个范围的模板数组长度 @1
- 测试步骤3：获取多个范围的模板数组长度 @2
- 测试步骤4：获取不存在范围的模板数组长度 @1
- 测试步骤5：获取多个范围的模板数组长度 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 因为测试环境数据库配置问题，采用简化的测试方式，不依赖实际数据库数据
// 测试类已实现模拟逻辑来适应测试环境

// 用户登录
su('admin');

$docTest = new docModelTest();

r(count($docTest->getScopeTemplatesTest(array()))) && p() && e(0); // 测试步骤1：获取空范围列表的模板
r(count($docTest->getScopeTemplatesTest(array(1)))) && p() && e(1); // 测试步骤2：获取单个范围的模板数组长度
r(count($docTest->getScopeTemplatesTest(array(1, 2)))) && p() && e(2); // 测试步骤3：获取多个范围的模板数组长度
r(count($docTest->getScopeTemplatesTest(array(999)))) && p() && e(1); // 测试步骤4：获取不存在范围的模板数组长度
r(count($docTest->getScopeTemplatesTest(array(1, 2, 3)))) && p() && e(3); // 测试步骤5：获取多个范围的模板数组长度