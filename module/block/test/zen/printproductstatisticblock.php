#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProductStatisticBlock();
timeout=0
cid=0

- 步骤1：正常产品统计区块测试属性productCount @5
- 步骤2：空数量参数测试属性productCount @0
- 步骤3：无效类型参数测试属性productCount @0
- 步骤4：边界值数量限制测试属性productCount @5
- 步骤5：空参数异常处理测试属性error @Missing block parameters

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（基础数据，避免复杂依赖）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->code->range('product1,product2,product3,product4,product5');
$product->status->range('normal');
$product->type->range('normal');
$product->deleted->range('0');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printProductStatisticBlockTest((object)array('params' => (object)array('type' => '', 'count' => 5)))) && p('productCount') && e('5'); // 步骤1：正常产品统计区块测试
r($blockTest->printProductStatisticBlockTest((object)array('params' => (object)array('type' => '', 'count' => 0)))) && p('productCount') && e('0'); // 步骤2：空数量参数测试
r($blockTest->printProductStatisticBlockTest((object)array('params' => (object)array('type' => 'invalid', 'count' => 5)))) && p('productCount') && e('0'); // 步骤3：无效类型参数测试
r($blockTest->printProductStatisticBlockTest((object)array('params' => (object)array('type' => '', 'count' => 999)))) && p('productCount') && e('5'); // 步骤4：边界值数量限制测试
r($blockTest->printProductStatisticBlockTest((object)array('params' => null))) && p('error') && e('Missing block parameters'); // 步骤5：空参数异常处理测试