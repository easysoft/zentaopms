#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getProductsForEdit();
timeout=0
cid=0

- 步骤1：管理员获取产品列表，应返回8个正常状态产品 @8
- 步骤2：验证管理员的产品存在属性1 @产品1
- 步骤3：user1用户获取产品列表 @8
- 步骤4：验证user1能看到产品2属性2 @产品2
- 步骤5：user2用户获取产品列表，验证关闭产品被过滤 @8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->PO->range('admin{3},user1{3},user2{2},admin{2}');
$table->status->range('normal{8},closed{2}');
$table->type->range('normal{10}');
$table->deleted->range('0{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($storyTest->getProductsForEditTest())) && p() && e('8'); // 步骤1：管理员获取产品列表，应返回8个正常状态产品
r($storyTest->getProductsForEditTest()) && p('1') && e('产品1'); // 步骤2：验证管理员的产品存在
su('user1');
r(count($storyTest->getProductsForEditTest())) && p() && e('8'); // 步骤3：user1用户获取产品列表
r($storyTest->getProductsForEditTest()) && p('2') && e('产品2'); // 步骤4：验证user1能看到产品2
su('user2');
r(count($storyTest->getProductsForEditTest())) && p() && e('8'); // 步骤5：user2用户获取产品列表，验证关闭产品被过滤