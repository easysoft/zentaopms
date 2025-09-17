#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildCasesForBathcCreate();
timeout=0
cid=0

- 步骤1：正常情况第0条的product属性 @1
- 步骤2：不同产品ID第0条的product属性 @2
- 步骤3：无效产品ID第0条的product属性 @0
- 步骤4：不存在的产品ID第0条的product属性 @999
- 步骤5：验证openedBy字段第0条的openedBy属性 @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->status->range('normal{5}');
$product->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->product->range('1-5');
$story->status->range('active{10}');
$story->version->range('1{10}');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->buildCasesForBathcCreateTest(1)) && p('0:product') && e('1'); // 步骤1：正常情况
r($testcaseTest->buildCasesForBathcCreateTest(2)) && p('0:product') && e('2'); // 步骤2：不同产品ID
r($testcaseTest->buildCasesForBathcCreateTest(0)) && p('0:product') && e('0'); // 步骤3：无效产品ID
r($testcaseTest->buildCasesForBathcCreateTest(999)) && p('0:product') && e('999'); // 步骤4：不存在的产品ID
r($testcaseTest->buildCasesForBathcCreateTest(1)) && p('0:openedBy') && e('admin'); // 步骤5：验证openedBy字段