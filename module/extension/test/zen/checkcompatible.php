#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkCompatible();
timeout=0
cid=0

- 步骤1：兼容版本检查测试 @0
- 步骤2：不兼容版本检查测试 @0
- 步骤3：忽略兼容性检查测试 @0
- 步骤4：不兼容版本列表检查测试 @0
- 步骤5：all版本兼容性检查测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 4. 构造测试数据
global $config;

// 创建兼容的条件对象（使用当前版本）
$compatibleCondition = new stdClass();
$compatibleCondition->zentao = array();
$compatibleCondition->zentao['compatible'] = $config->version;
$compatibleCondition->zentao['incompatible'] = '';

// 创建不兼容的条件对象（使用不存在的版本）
$incompatibleCondition = new stdClass();
$incompatibleCondition->zentao = array();
$incompatibleCondition->zentao['compatible'] = '99.99';
$incompatibleCondition->zentao['incompatible'] = '';

// 创建在不兼容列表中的条件对象（当前版本在不兼容列表中）
$incompatibleListCondition = new stdClass();
$incompatibleListCondition->zentao = array();
$incompatibleListCondition->zentao['compatible'] = $config->version;
$incompatibleListCondition->zentao['incompatible'] = $config->version;

// 创建all版本兼容的条件对象
$allCompatibleCondition = new stdClass();
$allCompatibleCondition->zentao = array();
$allCompatibleCondition->zentao['compatible'] = 'all';
$allCompatibleCondition->zentao['incompatible'] = '';

// 创建空兼容条件的对象
$emptyCondition = new stdClass();
$emptyCondition->zentao = array();
$emptyCondition->zentao['compatible'] = '';
$emptyCondition->zentao['incompatible'] = '';

// 5. 强制要求：必须包含至少5个测试步骤
r($extensionTest->checkCompatibleTest('test_extension', $compatibleCondition, 'no', '', 'install')) && p() && e('0'); // 步骤1：兼容版本检查测试
r($extensionTest->checkCompatibleTest('test_extension', $incompatibleCondition, 'no', 'testlink', 'install')) && p() && e('0'); // 步骤2：不兼容版本检查测试
r($extensionTest->checkCompatibleTest('test_extension', $incompatibleCondition, 'yes', '', 'install')) && p() && e('0'); // 步骤3：忽略兼容性检查测试
r($extensionTest->checkCompatibleTest('test_extension', $incompatibleListCondition, 'no', '', 'install')) && p() && e('0'); // 步骤4：不兼容版本列表检查测试
r($extensionTest->checkCompatibleTest('test_extension', $allCompatibleCondition, 'no', '', 'install')) && p() && e('0'); // 步骤5：all版本兼容性检查测试