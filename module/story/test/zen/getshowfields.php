#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getShowFields();
timeout=0
cid=18696

- 步骤1：normal产品移除branch但platform前逗号也被移除 @id,titleplatform,status

- 步骤2：normal产品无branch和platform字段 @id,title,status

- 步骤3：branch产品保持所有字段 @id,title,branch,platform,status

- 步骤4：空字段列表返回0 @0
- 步骤5：其他类型产品保持所有字段 @id,title,branch,platform,status

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product{1-5}');
$product->type->range('normal{2},branch{2},other{1}');
$product->status->range('normal');
$product->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyZenTest = new storyZenTest();

// 准备产品对象
global $tester;
$productModel = $tester->loadModel('product');

// 5. 测试步骤 - 必须包含至少5个测试步骤
r($storyZenTest->getShowFieldsTest('id,title,branch,platform,status', 'story', $productModel->getByID(1))) && p() && e('id,titleplatform,status'); // 步骤1：normal产品移除branch但platform前逗号也被移除
r($storyZenTest->getShowFieldsTest('id,title,status', 'story', $productModel->getByID(1))) && p() && e('id,title,status'); // 步骤2：normal产品无branch和platform字段
r($storyZenTest->getShowFieldsTest('id,title,branch,platform,status', 'story', $productModel->getByID(3))) && p() && e('id,title,branch,platform,status'); // 步骤3：branch产品保持所有字段
r($storyZenTest->getShowFieldsTest('', 'story', $productModel->getByID(1))) && p() && e('0'); // 步骤4：空字段列表返回0
r($storyZenTest->getShowFieldsTest('id,title,branch,platform,status', 'story', $productModel->getByID(5))) && p() && e('id,title,branch,platform,status'); // 步骤5：其他类型产品保持所有字段