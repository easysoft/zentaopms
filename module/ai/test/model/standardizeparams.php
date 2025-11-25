#!/usr/bin/env php
<?php

/**

title=测试 aiModel::standardizeParams();
timeout=0
cid=15067

- 步骤1：正常对象参数转换
 - 属性camel_case_property @value1
 - 属性another_camel_case @value2
- 步骤2：空对象参数处理 @0
- 步骤3：单个属性对象转换属性user_name @test
- 步骤4：包含数字的属性转换属性property123_name @number
- 步骤5：复杂驼峰命名转换属性very_long_property_name_with_many_words @complex

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：正常对象参数转换 - 检查结果是否为对象
$normalData = new stdClass();
$normalData->camelCaseProperty = 'value1';
$normalData->anotherCamelCase = 'value2';
r($aiTest->standardizeParamsTest($normalData)) && p('camel_case_property,another_camel_case') && e('value1,value2'); // 步骤1：正常对象参数转换

// 步骤2：空对象参数处理 - 检查属性数量
$emptyData = new stdClass();
$result2 = $aiTest->standardizeParamsTest($emptyData);
r(count(get_object_vars($result2))) && p() && e(0); // 步骤2：空对象参数处理

// 步骤3：单个属性对象转换
$singleData = new stdClass();
$singleData->userName = 'test';
r($aiTest->standardizeParamsTest($singleData)) && p('user_name') && e('test'); // 步骤3：单个属性对象转换

// 步骤4：包含数字的属性转换
$numberData = new stdClass();
$numberData->property123Name = 'number';
r($aiTest->standardizeParamsTest($numberData)) && p('property123_name') && e('number'); // 步骤4：包含数字的属性转换

// 步骤5：复杂驼峰命名转换
$complexData = new stdClass();
$complexData->veryLongPropertyNameWithManyWords = 'complex';
r($aiTest->standardizeParamsTest($complexData)) && p('very_long_property_name_with_many_words') && e('complex'); // 步骤5：复杂驼峰命名转换