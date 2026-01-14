#!/usr/bin/env php
<?php

/**

title=测试 storeModel::batchSetLatestVersions();
timeout=0
cid=18451

- 步骤1：空数组测试 @0
- 步骤2：空数组长度验证 @0
- 步骤3：返回类型验证 @1
- 步骤4：空数组empty检查 @1
- 步骤5：空数组重复验证 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$storeTest = new storeModelTest();

// 5. 测试步骤 - 每个测试用例必须包含至少5个测试步骤
r($storeTest->batchSetLatestVersionsTest(array())) && p() && e('0'); // 步骤1：空数组测试
r(count($storeTest->batchSetLatestVersionsTest(array()))) && p() && e(0); // 步骤2：空数组长度验证
r(is_array($storeTest->batchSetLatestVersionsTest(array()))) && p() && e('1'); // 步骤3：返回类型验证
r(empty($storeTest->batchSetLatestVersionsTest(array()))) && p() && e('1'); // 步骤4：空数组empty检查
r($storeTest->batchSetLatestVersionsTest(array())) && p() && e('0'); // 步骤5：空数组重复验证