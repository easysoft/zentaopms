#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getActiveProductCard();
timeout=0
cid=0

- 步骤1：测试方法调用正常第0条的count属性 @0
- 步骤2：验证年月参数正确传递
 - 第0条的year属性 @2023
 - 第0条的month属性 @12
- 步骤3：验证不同年月参数
 - 第0条的year属性 @2024
 - 第0条的month属性 @1
- 步骤4：测试未来时间第0条的count属性 @0
- 步骤5：测试空参数处理第0条的count属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('Product{5}');
$productTable->deleted->range('0');
$productTable->shadow->range('0');
$productTable->gen(5);

$actionTable = zenData('action');
$actionTable->id->range('1-5');
$actionTable->objectType->range('story{5}');
$actionTable->product->range(',0,{5}');
$actionTable->date->range('2023-12-01{5}');
$actionTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$screenTest = new screenTest();

// 5. 执行测试步骤（至少5个）
r($screenTest->getActiveProductCardTest('2023', '12')) && p('0:count') && e('0'); // 步骤1：测试方法调用正常
r($screenTest->getActiveProductCardTest('2023', '12')) && p('0:year,month') && e('2023,12'); // 步骤2：验证年月参数正确传递
r($screenTest->getActiveProductCardTest('2024', '1')) && p('0:year,month') && e('2024,1'); // 步骤3：验证不同年月参数
r($screenTest->getActiveProductCardTest('2025', '06')) && p('0:count') && e('0'); // 步骤4：测试未来时间
r($screenTest->getActiveProductCardTest('', '')) && p('0:count') && e('0'); // 步骤5：测试空参数处理