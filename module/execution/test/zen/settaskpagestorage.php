#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setTaskPageStorage();
timeout=0
cid=16444

- 步骤1：正常情况 @1
- 步骤2：bymodule类型 @1
- 步骤3：byproduct类型 @1
- 步骤4：其他类型 @1
- 步骤5：边界值 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->setTaskPageStorageTest(1, 'id_desc', 'all', 0)) && p() && e('1'); // 步骤1：正常情况
r($executionTest->setTaskPageStorageTest(2, 'pri_asc', 'bymodule', 10)) && p() && e('1'); // 步骤2：bymodule类型
r($executionTest->setTaskPageStorageTest(3, 'status_desc', 'byproduct', 20)) && p() && e('1'); // 步骤3：byproduct类型
r($executionTest->setTaskPageStorageTest(4, 'name_asc', 'unclosed', 0)) && p() && e('1'); // 步骤4：其他类型
r($executionTest->setTaskPageStorageTest(0, '', 'all', -1)) && p() && e('1'); // 步骤5：边界值