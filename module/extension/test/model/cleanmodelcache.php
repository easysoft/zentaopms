#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::cleanModelCache();
timeout=0
cid=16451

- 步骤1：正常清理缓存 @1
- 步骤2：重复清理缓存 @1
- 步骤3：无缓存文件时清理 @1
- 步骤4：继续清理确认稳定性 @1
- 步骤5：最终验证清理功能 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->cleanModelCacheTest()) && p() && e('1'); // 步骤1：正常清理缓存
r($extensionTest->cleanModelCacheTest()) && p() && e('1'); // 步骤2：重复清理缓存
r($extensionTest->cleanModelCacheTest()) && p() && e('1'); // 步骤3：无缓存文件时清理
r($extensionTest->cleanModelCacheTest()) && p() && e('1'); // 步骤4：继续清理确认稳定性
r($extensionTest->cleanModelCacheTest()) && p() && e('1'); // 步骤5：最终验证清理功能