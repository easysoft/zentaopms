#!/usr/bin/env php
<?php

/**

title=测试 biModel::json2Array();
timeout=0
cid=0

- 步骤1：null值输入 @array
- 步骤2：有效JSON字符串
 - 属性name @test
 - 属性value @123
- 步骤3：对象输入
 - 属性key @value
 - 属性num @456
- 步骤4：数组输入
 -  @item1
 - 属性1 @item2
 - 属性2 @item3
- 步骤5：空字符串输入 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($biTest->json2ArrayTest(null)) && p() && e('array'); // 步骤1：null值输入
r($biTest->json2ArrayTest('{"name":"test","value":123}')) && p('name,value') && e('test,123'); // 步骤2：有效JSON字符串
r($biTest->json2ArrayTest((object)array('key' => 'value', 'num' => 456))) && p('key,num') && e('value,456'); // 步骤3：对象输入
r($biTest->json2ArrayTest(array('item1', 'item2', 'item3'))) && p('0,1,2') && e('item1,item2,item3'); // 步骤4：数组输入
r($biTest->json2ArrayTest('')) && p() && e('array'); // 步骤5：空字符串输入