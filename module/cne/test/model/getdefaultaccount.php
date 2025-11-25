#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDefaultAccount();
timeout=0
cid=15617

- 步骤1：正常情况测试获取默认账号（空组件参数） @0
- 步骤2：使用mysql组件获取默认账号 @0
- 步骤3：使用redis组件获取默认账号 @0
- 步骤4：使用null实例参数 @0
- 步骤5：使用无效实例对象验证容错性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 创建模拟实例对象
$instance1 = new stdclass();
$instance1->id = 1;
$instance1->k8name = 'test-zentao-app-1';
$instance1->channel = 'stable';
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace';

$instance2 = new stdclass();
$instance2->id = 2;
$instance2->k8name = 'test-zentao-app-2';
$instance2->channel = 'stable';
$instance2->spaceData = new stdclass();
$instance2->spaceData->k8space = 'test-namespace';

$invalidInstance = new stdclass();
$invalidInstance->id = 999;
// 缺少必需的属性

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getDefaultAccountTest($instance1, '')) && p() && e('0'); // 步骤1：正常情况测试获取默认账号（空组件参数）
r($cneTest->getDefaultAccountTest($instance1, 'mysql')) && p() && e('0'); // 步骤2：使用mysql组件获取默认账号
r($cneTest->getDefaultAccountTest($instance2, 'redis')) && p() && e('0'); // 步骤3：使用redis组件获取默认账号
r($cneTest->getDefaultAccountTest(null)) && p() && e('0'); // 步骤4：使用null实例参数
r($cneTest->getDefaultAccountTest($invalidInstance, 'invalid-component')) && p() && e('0'); // 步骤5：使用无效实例对象验证容错性