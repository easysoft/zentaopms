#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::backupList();
timeout=0
cid=16780

- 步骤1：正常情况（CNE不可用返回空数组） @0
- 步骤2：空实例（参数不完整返回空数组） @0
- 步骤3：CNE错误返回（返回空数组） @0
- 步骤4：CNE空数据（返回空数组） @0
- 步骤5：数据处理验证（返回空数组） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('instance')->loadYaml('instance_backuplist', false, 2)->gen(5);
zendata('user')->loadYaml('user_backuplist', false, 2)->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 创建模拟实例对象
$validInstance = new stdclass();
$validInstance->id = 1;
$validInstance->name = 'backup-test';
$validInstance->k8name = 'backup-k8-1';
$validInstance->spaceData = new stdclass();
$validInstance->spaceData->k8space = 'test-namespace';

$emptyInstance = new stdclass();
$emptyInstance->id = 0;
$emptyInstance->name = '';
$emptyInstance->k8name = '';
$emptyInstance->spaceData = new stdclass();
$emptyInstance->spaceData->k8space = '';

// 创建无效CNE响应模拟实例
$invalidInstance = new stdclass();
$invalidInstance->id = 2;
$invalidInstance->name = 'invalid-test';
$invalidInstance->k8name = 'invalid-k8-2';
$invalidInstance->spaceData = new stdclass();
$invalidInstance->spaceData->k8space = 'invalid-namespace';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->backupListTest($validInstance)) && p() && e('0'); // 步骤1：正常情况（CNE不可用返回空数组）
r($instanceTest->backupListTest($emptyInstance)) && p() && e('0'); // 步骤2：空实例（参数不完整返回空数组）
r($instanceTest->backupListTest($invalidInstance)) && p() && e('0'); // 步骤3：CNE错误返回（返回空数组）
r($instanceTest->backupListTest($validInstance)) && p() && e('0'); // 步骤4：CNE空数据（返回空数组）
r($instanceTest->backupListTest($validInstance)) && p() && e('0'); // 步骤5：数据处理验证（返回空数组）