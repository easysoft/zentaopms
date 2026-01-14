#!/usr/bin/env php
<?php

/**

title=测试 cneModel::appDBDetail();
timeout=0
cid=15601

- 步骤1：正常实例但无外部API连接 @0
- 步骤2：空实例对象 @0
- 步骤3：缺少k8name属性的实例 @0
- 步骤4：缺少spaceData属性的实例 @0
- 步骤5：空数据库名称参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 创建测试实例
$cneTest = new cneModelTest();

// 3. 创建模拟实例对象用于测试
$instance1 = new stdClass();
$instance1->k8name = 'test-zentao-app';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'test-namespace';

$instance2 = null; // 空实例对象

$instance3 = new stdClass();
$instance3->spaceData = new stdClass();
$instance3->spaceData->k8space = 'test-namespace';
// 缺少k8name属性

$instance4 = new stdClass();
$instance4->k8name = 'test-app';
// 缺少spaceData属性

$instance5 = new stdClass();
$instance5->k8name = 'test-app';
$instance5->spaceData = new stdClass();
$instance5->spaceData->k8space = 'test-namespace';

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->appDBDetailTest($instance1, 'zentao')) && p() && e('0'); // 步骤1：正常实例但无外部API连接
r($cneTest->appDBDetailTest($instance2, 'test_db')) && p() && e('0'); // 步骤2：空实例对象
r($cneTest->appDBDetailTest($instance3, 'zentao')) && p() && e('0'); // 步骤3：缺少k8name属性的实例
r($cneTest->appDBDetailTest($instance4, 'zentao')) && p() && e('0'); // 步骤4：缺少spaceData属性的实例
r($cneTest->appDBDetailTest($instance5, '')) && p() && e('0'); // 步骤5：空数据库名称参数