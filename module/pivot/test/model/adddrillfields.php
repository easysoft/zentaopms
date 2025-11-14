#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addDrillFields();
timeout=0
cid=17353

- 步骤1:cell包含value且无drillFields第drillFields条的field1属性 @value1
- 步骤2:cell包含value且已有drillFields第drillFields条的field1属性 @value1
- 步骤3:递归处理子元素
 - 第slice1条的value属性 @100
 - 第slice2条的value属性 @200
- 步骤4:包含total键应跳过
 - 第total条的value属性 @999
 - 第slice1条的value属性 @100
- 步骤5:drillFields为空数组第drillFields条的field0属性 @value0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$pivotTest = new pivotTest();

// 4. 强制要求:必须包含至少5个测试步骤
r($pivotTest->addDrillFieldsTest(array('value' => 100), array('field1' => 'value1'))) && p('drillFields:field1') && e('value1'); // 步骤1:cell包含value且无drillFields
r($pivotTest->addDrillFieldsTest(array('value' => 100, 'drillFields' => array('field0' => 'value0')), array('field1' => 'value1'))) && p('drillFields:field1') && e('value1'); // 步骤2:cell包含value且已有drillFields
r($pivotTest->addDrillFieldsTest(array('slice1' => array('value' => 100), 'slice2' => array('value' => 200)), array('field1' => 'value1'))) && p('slice1:value;slice2:value') && e('100;200'); // 步骤3:递归处理子元素
r($pivotTest->addDrillFieldsTest(array('total' => array('value' => 999), 'slice1' => array('value' => 100)), array('field1' => 'value1'))) && p('total:value;slice1:value') && e('999;100'); // 步骤4:包含total键应跳过
r($pivotTest->addDrillFieldsTest(array('value' => 100, 'drillFields' => array('field0' => 'value0')), array())) && p('drillFields:field0') && e('value0'); // 步骤5:drillFields为空数组