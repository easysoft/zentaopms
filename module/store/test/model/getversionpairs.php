#!/usr/bin/env php
<?php

/**

title=测试 storeModel::getVersionPairs();
timeout=0
cid=18456

- 步骤1：正常应用ID @0
- 步骤2：不存在的应用ID @0
- 步骤3：应用ID为0 @0
- 步骤4：负数应用ID @0
- 步骤5：大数值应用ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/store.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storeTest = new storeTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($storeTest->getVersionPairsTest(1)) && p() && e(0); // 步骤1：正常应用ID
r($storeTest->getVersionPairsTest(999999)) && p() && e(0); // 步骤2：不存在的应用ID
r($storeTest->getVersionPairsTest(0)) && p() && e(0); // 步骤3：应用ID为0
r($storeTest->getVersionPairsTest(-1)) && p() && e(0); // 步骤4：负数应用ID
r($storeTest->getVersionPairsTest(2147483647)) && p() && e(0); // 步骤5：大数值应用ID