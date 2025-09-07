#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareDrillConditions();
timeout=0
cid=0

- 步骤1：正常情况
 - 第1条的0:value属性 @value1
 - 第1条的1:1:value属性 @value2
- 步骤2：空条件数组
 -  @origin_field
 - 属性1 @Array
- 步骤3：不匹配的查询字段第1条的0:value属性 @old_value
- 步骤4：空钻取字段数组第1条的0:value属性 @old_value
- 步骤5：混合情况
 - 第1条的0:value属性 @value1
 - 第1条的1:1:value属性 @old_value2

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$biTest = new biTest();

// 4. 测试步骤
r($biTest->prepareDrillConditionsTest(array('field1' => 'value1', 'field2' => 'value2'), array(array('queryField' => 'field1', 'value' => 'old_value1'), array('queryField' => 'field2', 'value' => 'old_value2')), 'origin_field')) && p('1:0:value,1:1:value') && e('value1,value2'); // 步骤1：正常情况

r($biTest->prepareDrillConditionsTest(array('field1' => 'value1'), array(), 'origin_field')) && p('0,1') && e('origin_field,Array'); // 步骤2：空条件数组

r($biTest->prepareDrillConditionsTest(array('field1' => 'value1'), array(array('queryField' => 'field2', 'value' => 'old_value')), 'origin_field')) && p('1:0:value') && e('old_value'); // 步骤3：不匹配的查询字段

r($biTest->prepareDrillConditionsTest(array(), array(array('queryField' => 'field1', 'value' => 'old_value')), 'origin_field')) && p('1:0:value') && e('old_value'); // 步骤4：空钻取字段数组

r($biTest->prepareDrillConditionsTest(array('field1' => 'value1'), array(array('queryField' => 'field1', 'value' => 'old_value1'), array('queryField' => 'field2', 'value' => 'old_value2')), 'origin_field')) && p('1:0:value,1:1:value') && e('value1,old_value2'); // 步骤5：混合情况