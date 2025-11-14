#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildOptionconfigurationData();
timeout=0
cid=15820

- 步骤1：完整数据测试
 - 属性id @1
 - 属性fieldid @field1
 - 属性optionid @option1
 - 属性fieldconfig @config1
- 步骤2：最小必填数据测试
 - 属性id @2
 - 属性fieldid @~~
 - 属性optionid @~~
 - 属性fieldconfig @~~
- 步骤3：部分字段缺失测试
 - 属性id @3
 - 属性fieldid @field3
 - 属性optionid @option3
 - 属性fieldconfig @~~
- 步骤4：空值字段处理测试
 - 属性id @4
 - 属性fieldid @~~
 - 属性optionid @~~
 - 属性fieldconfig @0
- 步骤5：数值类型字段测试
 - 属性id @5
 - 属性fieldid @123
 - 属性optionid @456
 - 属性fieldconfig @789

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildOptionconfigurationDataTest(array('id' => 1, 'fieldid' => 'field1', 'optionid' => 'option1', 'fieldconfig' => 'config1'))) && p('id,fieldid,optionid,fieldconfig') && e('1,field1,option1,config1'); // 步骤1：完整数据测试
r($convertTest->buildOptionconfigurationDataTest(array('id' => 2))) && p('id,fieldid,optionid,fieldconfig') && e('2,~~,~~,~~'); // 步骤2：最小必填数据测试
r($convertTest->buildOptionconfigurationDataTest(array('id' => 3, 'fieldid' => 'field3', 'optionid' => 'option3'))) && p('id,fieldid,optionid,fieldconfig') && e('3,field3,option3,~~'); // 步骤3：部分字段缺失测试
r($convertTest->buildOptionconfigurationDataTest(array('id' => 4, 'fieldid' => '', 'optionid' => null, 'fieldconfig' => '0'))) && p('id,fieldid,optionid,fieldconfig') && e('4,~~,~~,0'); // 步骤4：空值字段处理测试
r($convertTest->buildOptionconfigurationDataTest(array('id' => 5, 'fieldid' => 123, 'optionid' => 456, 'fieldconfig' => 789))) && p('id,fieldid,optionid,fieldconfig') && e('5,123,456,789'); // 步骤5：数值类型字段测试