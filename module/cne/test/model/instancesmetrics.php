#!/usr/bin/env php
<?php

/**

title=测试 cneModel::instancesMetrics();
timeout=0
cid=0

- 步骤1：空实例数组测试 @0
- 步骤2：正常实例数组测试，包含磁盘指标 @2
- 步骤3：正常实例数组测试，不包含磁盘指标 @2
- 步骤4：包含external类型实例的数组测试 @2
- 步骤5：单个有效实例测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('instance')->loadYaml('instance', false, 2)->gen(3);
zendata('space')->loadYaml('space', false, 1)->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 准备测试数据
$emptyInstances = array();

// 创建有效实例数据
$validInstance1 = new stdclass();
$validInstance1->id = 1;
$validInstance1->k8name = 'test-instance-1';
$validInstance1->source = 'internal';
$validInstance1->spaceData = new stdclass();
$validInstance1->spaceData->k8space = 'test-namespace';

$validInstance2 = new stdclass();
$validInstance2->id = 2;
$validInstance2->k8name = 'test-instance-2';
$validInstance2->source = 'internal';
$validInstance2->spaceData = new stdclass();
$validInstance2->spaceData->k8space = 'test-namespace';

// 创建external实例数据
$externalInstance = new stdclass();
$externalInstance->id = 3;
$externalInstance->k8name = 'external-instance';
$externalInstance->source = 'external';
$externalInstance->spaceData = new stdclass();
$externalInstance->spaceData->k8space = 'test-namespace';

$validInstances = array($validInstance1, $validInstance2);
$mixedInstances = array($validInstance1, $externalInstance, $validInstance2);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->instancesMetricsTest($emptyInstances, true)) && p() && e('0'); // 步骤1：空实例数组测试
r($cneTest->instancesMetricsTest($validInstances, true)) && p() && e('2'); // 步骤2：正常实例数组测试，包含磁盘指标
r($cneTest->instancesMetricsTest($validInstances, false)) && p() && e('2'); // 步骤3：正常实例数组测试，不包含磁盘指标
r($cneTest->instancesMetricsTest($mixedInstances, true)) && p() && e('2'); // 步骤4：包含external类型实例的数组测试
r($cneTest->instancesMetricsTest(array($validInstance1), true)) && p() && e('1'); // 步骤5：单个有效实例测试