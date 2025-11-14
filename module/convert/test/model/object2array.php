#!/usr/bin/env php
<?php

/**

title=测试 convertModel::object2Array();
timeout=0
cid=15793

- 步骤1：正常对象转换
 - 属性name @test
 - 属性id @123
- 步骤2：嵌套对象转换 @nested
- 步骤3：空对象转换为空数组 @1
- 步骤4：混合结构转换 @test1
- 步骤5：纯数组数据处理属性key1 @value1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试简单对象转换为数组
$simpleObject = new stdClass();
$simpleObject->name = 'test';
$simpleObject->id = 123;
r($convertTest->object2ArrayTest($simpleObject)) && p('name,id') && e('test,123'); // 步骤1：正常对象转换

// 步骤2：测试复杂嵌套对象转换
$complexObject = new stdClass();
$complexObject->level1 = new stdClass();
$complexObject->level1->level2 = new stdClass();
$complexObject->level1->level2->value = 'nested';
$complexObject->array = array('item1', 'item2');
$result2 = $convertTest->object2ArrayTest($complexObject);
r($result2['level1']['level2']['value']) && p() && e('nested'); // 步骤2：嵌套对象转换

// 步骤3：测试包含空对象的转换
$objectWithEmpty = new stdClass();
$objectWithEmpty->name = 'test';
$objectWithEmpty->empty = new stdClass();
$result = $convertTest->object2ArrayTest($objectWithEmpty);
r(empty($result['empty']) && is_array($result['empty'])) && p() && e('1'); // 步骤3：空对象转换为空数组

// 步骤4：测试包含数组和对象混合的复杂结构
$mixedStructure = array(
    'objects' => array(
        'obj1' => new stdClass(),
        'obj2' => (object)array('prop' => 'value')
    ),
    'simple' => 'string'
);
$mixedStructure['objects']['obj1']->name = 'test1';
$result4 = $convertTest->object2ArrayTest($mixedStructure);
r($result4['objects']['obj1']['name']) && p() && e('test1'); // 步骤4：混合结构转换

// 步骤5：测试已经是数组的数据（边界情况）
$arrayData = array(
    'key1' => 'value1',
    'key2' => array('nested' => 'value2')
);
r($convertTest->object2ArrayTest($arrayData)) && p('key1') && e('value1'); // 步骤5：纯数组数据处理