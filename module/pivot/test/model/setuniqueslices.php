#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::setUniqueSlices();
timeout=0
cid=17434

- 步骤1：正常分片字段category属性slice @category
- 步骤2：noSlice情况不添加uniqueSlices属性slice @noSlice
- 步骤3：空records数组处理属性slice @category
- 步骤4：不同字段priority分片属性slice @priority
- 步骤5：验证uniqueSlices数量 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->setUniqueSlicesTest(null, array('slice' => 'category'))) && p('slice') && e('category'); // 步骤1：正常分片字段category
r($pivotTest->setUniqueSlicesTest(null, array('slice' => 'noSlice'))) && p('slice') && e('noSlice'); // 步骤2：noSlice情况不添加uniqueSlices
r($pivotTest->setUniqueSlicesTest(array(), array('slice' => 'category'))) && p('slice') && e('category'); // 步骤3：空records数组处理
r($pivotTest->setUniqueSlicesTest(null, array('slice' => 'priority'))) && p('slice') && e('priority'); // 步骤4：不同字段priority分片
r(count($pivotTest->setUniqueSlicesTest(null, array('slice' => 'category'))['uniqueSlices'])) && p() && e('2'); // 步骤5：验证uniqueSlices数量