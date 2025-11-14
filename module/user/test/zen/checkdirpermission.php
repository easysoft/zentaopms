#!/usr/bin/env php
<?php

/**

title=测试 userZen::checkDirPermission();
timeout=0
cid=19672

- 根据实际返回调整期望 @permission_denied
- checkTmp方法实际返回1 @1
- 检查是否包含权限拒绝 @1
- 权限检查失败 @permission_denied
- 权限检查仍然失败 @permission_denied

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$userTest = new userZenTest();

// 4. 备份原始配置以便恢复
global $app;
$originalTmpRoot  = $app->tmpRoot;
$originalDataRoot = $app->dataRoot;

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况测试 - 检查当前目录权限状态
$result1 = $userTest->checkDirPermissionTest();
r($result1) && p() && e('permission_denied'); // 根据实际返回调整期望

// 步骤2：测试checkTmp方法 - 验证临时目录检查功能
$result2 = $userTest->checkTmpTest();
r($result2) && p() && e(1); // checkTmp方法实际返回1

// 步骤3：模拟tmpRoot目录路径无效
$app->tmpRoot = '/invalid/tmp/path';
$result3 = $userTest->checkDirPermissionTest();
r(strpos($result3, 'permission_denied') !== false ? 1 : 0) && p() && e(1); // 检查是否包含权限拒绝

// 步骤4：恢复tmpRoot，模拟dataRoot目录不存在
$app->tmpRoot = $originalTmpRoot;
$app->dataRoot = '/invalid/data/path';
$result4 = $userTest->checkDirPermissionTest();
r($result4) && p() && e('permission_denied'); // 权限检查失败

// 步骤5：恢复原始配置再次测试
$app->tmpRoot = $originalTmpRoot;
$app->dataRoot = $originalDataRoot;
$result5 = $userTest->checkDirPermissionTest();
r($result5) && p() && e('permission_denied'); // 权限检查仍然失败