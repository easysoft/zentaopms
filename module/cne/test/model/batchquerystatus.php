#!/usr/bin/env php
<?php

/**

title=测试 cneModel::batchQueryStatus();
timeout=0
cid=0

- 步骤1：正常实例列表 @2
- 步骤2：空实例列表 @0
- 步骤3：单个实例 @1
- 步骤4：空channel实例第empty-channel-app条的status属性 @running
- 步骤5：默认模拟数据 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于测试使用模拟数据，不需要生成数据库数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常实例列表
$instance1 = new stdclass();
$instance1->k8name = 'test-app-1';
$instance1->chart = 'zentao';
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace-1';
$instance1->channel = 'stable';

$instance2 = new stdclass();
$instance2->k8name = 'test-app-2';
$instance2->chart = 'sonarqube';
$instance2->spaceData = new stdclass();
$instance2->spaceData->k8space = 'test-namespace-2';
$instance2->channel = 'beta';

r($cneTest->batchQueryStatusTest(array($instance1, $instance2))) && p() && e('2'); // 步骤1：正常实例列表

// 步骤2：测试空实例列表
r($cneTest->batchQueryStatusTest(array())) && p() && e('0'); // 步骤2：空实例列表

// 步骤3：测试单个实例
$singleInstance = new stdclass();
$singleInstance->k8name = 'single-app';
$singleInstance->chart = 'gitlab';
$singleInstance->spaceData = new stdclass();
$singleInstance->spaceData->k8space = 'single-namespace';
$singleInstance->channel = 'stable';

r($cneTest->batchQueryStatusTest(array($singleInstance))) && p() && e('1'); // 步骤3：单个实例

// 步骤4：测试包含空channel的实例
$emptyChannelInstance = new stdclass();
$emptyChannelInstance->k8name = 'empty-channel-app';
$emptyChannelInstance->chart = 'zentao';
$emptyChannelInstance->spaceData = new stdclass();
$emptyChannelInstance->spaceData->k8space = 'empty-channel-namespace';
$emptyChannelInstance->channel = ''; // 空channel

r($cneTest->batchQueryStatusTest(array($emptyChannelInstance))) && p('empty-channel-app:status') && e('running'); // 步骤4：空channel实例

// 步骤5：测试默认场景（无参数传入，使用默认模拟数据）
r($cneTest->batchQueryStatusTest()) && p() && e('2'); // 步骤5：默认模拟数据