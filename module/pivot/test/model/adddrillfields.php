#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addDrillFields();
timeout=0
cid=0

- 步骤1：正常情况-基本值保持不变属性value @10
- 步骤2：已有drillFields情况属性value @20
- 步骤3：嵌套结构递归处理第slice1条的value属性 @15
- 步骤4：多层嵌套递归 @Array
- 步骤5：基础数值测试属性value @100

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->addDrillFieldsTest(array('value' => 10), array('field1' => 'value1', 'field2' => 'value2'))) && p('value') && e('10'); // 步骤1：正常情况-基本值保持不变
r($pivotTest->addDrillFieldsTest(array('value' => 20, 'drillFields' => array('existing' => 'data')), array('new' => 'field'))) && p('value') && e('20'); // 步骤2：已有drillFields情况
r($pivotTest->addDrillFieldsTest(array('slice1' => array('value' => 15), 'slice2' => array('value' => 25), 'total' => 40), array('category' => 'test'))) && p('slice1:value') && e('15'); // 步骤3：嵌套结构递归处理
r($pivotTest->addDrillFieldsTest(array('level1' => array('level2' => array('value' => 30))), array('depth' => 'deep'))) && p() && e('Array'); // 步骤4：多层嵌套递归
r($pivotTest->addDrillFieldsTest(array('value' => 100), array('key' => 'val'))) && p('value') && e('100'); // 步骤5：基础数值测试