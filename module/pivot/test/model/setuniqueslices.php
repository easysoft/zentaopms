#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::setUniqueSlices();
timeout=0
cid=0

- 步骤1：正常情况返回slice字段属性slice @category
- 步骤2：noSlice情况属性slice @noSlice
- 步骤3：空records属性slice @category
- 步骤4：不同字段属性slice @priority
- 步骤5：缓存机制验证属性slice @category

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->setUniqueSlicesTest('category')) && p('slice') && e('category'); // 步骤1：正常情况返回slice字段
r($pivotTest->setUniqueSlicesTest('noSlice')) && p('slice') && e('noSlice'); // 步骤2：noSlice情况
r($pivotTest->setUniqueSlicesTest('category', array())) && p('slice') && e('category'); // 步骤3：空records
r($pivotTest->setUniqueSlicesTest('priority')) && p('slice') && e('priority'); // 步骤4：不同字段
r($pivotTest->setUniqueSlicesTest('category')) && p('slice') && e('category'); // 步骤5：缓存机制验证