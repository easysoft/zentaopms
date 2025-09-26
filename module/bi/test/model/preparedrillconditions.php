#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareDrillConditions();
timeout=0
cid=0

- 步骤1：正常情况测试钻取字段匹配 @origin_field
- 步骤2：空条件数组测试 @origin_field
- 步骤3：不匹配的查询字段测试 @origin_field
- 步骤4：空钻取字段数组测试 @origin_field
- 步骤5：部分匹配的混合情况测试 @origin_field

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$biTest = new biTest();

// 4. 测试步骤（每个测试步骤必须在一行内）
r($biTest->prepareDrillConditionsTest(array('field1' => 'value1', 'field2' => 'value2'), array(array('queryField' => 'field1', 'value' => 'old_value1'), array('queryField' => 'field2', 'value' => 'old_value2')), 'origin_field')) && p('0') && e('origin_field'); // 步骤1：正常情况测试钻取字段匹配

r($biTest->prepareDrillConditionsTest(array('field1' => 'value1'), array(), 'origin_field')) && p('0') && e('origin_field'); // 步骤2：空条件数组测试

r($biTest->prepareDrillConditionsTest(array('field1' => 'value1'), array(array('queryField' => 'field2', 'value' => 'old_value')), 'origin_field')) && p('0') && e('origin_field'); // 步骤3：不匹配的查询字段测试

r($biTest->prepareDrillConditionsTest(array(), array(array('queryField' => 'field1', 'value' => 'old_value')), 'origin_field')) && p('0') && e('origin_field'); // 步骤4：空钻取字段数组测试

r($biTest->prepareDrillConditionsTest(array('field1' => 'value1'), array(array('queryField' => 'field1', 'value' => 'old_value1'), array('queryField' => 'field2', 'value' => 'old_value2')), 'origin_field')) && p('0') && e('origin_field'); // 步骤5：部分匹配的混合情况测试