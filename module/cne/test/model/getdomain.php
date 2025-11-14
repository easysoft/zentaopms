#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDomain();
timeout=0
cid=15619

- 步骤1：使用默认实例获取域名信息 @0
- 步骤2：使用默认实例和空组件名获取域名 @0
- 步骤3：使用默认实例和mysql组件获取域名 @0
- 步骤4：使用默认实例和web组件获取域名 @0
- 步骤5：使用无效实例对象获取域名 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 3. 准备测试数据：创建模拟实例对象
$validInstance = new stdclass();
$validInstance->id = 2;
$validInstance->k8name = 'test-zentao-app';
$validInstance->channel = 'stable';
$validInstance->spaceData = new stdclass();
$validInstance->spaceData->k8space = 'test-namespace';

$invalidInstance = new stdclass();
$invalidInstance->id = 999;
$invalidInstance->k8name = '';

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getDomainTest($validInstance, '')) && p() && e('0'); // 步骤1：使用默认实例获取域名信息
r($cneTest->getDomainTest($validInstance)) && p() && e('0'); // 步骤2：使用默认实例和空组件名获取域名
r($cneTest->getDomainTest($validInstance, 'mysql')) && p() && e('0'); // 步骤3：使用默认实例和mysql组件获取域名
r($cneTest->getDomainTest($validInstance, 'web')) && p() && e('0'); // 步骤4：使用默认实例和web组件获取域名
r($cneTest->getDomainTest($invalidInstance, '')) && p() && e('0'); // 步骤5：使用无效实例对象获取域名