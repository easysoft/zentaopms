#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForActivate();
timeout=0
cid=0

- 步骤1：正常激活产品属性status @normal
- 步骤2：OR视角激活产品属性status @normal
- 步骤3：激活带描述内容的产品属性desc @~~
- 步骤4：激活产品ID为0 @0
- 步骤5：激活产品ID为负数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->status->range('closed{5}');
$table->desc->range('描述1,描述2,描述3,描述4,描述5');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildProductForActivateTest(1)) && p('status') && e('normal');                      // 步骤1：正常激活产品
r($productTest->buildProductForActivateTest(2, 'or')) && p('status') && e('normal');                 // 步骤2：OR视角激活产品
r($productTest->buildProductForActivateTest(3)) && p('desc') && e('~~');                            // 步骤3：激活带描述内容的产品
r($productTest->buildProductForActivateTest(0)) && p() && e('0');                                   // 步骤4：激活产品ID为0
r($productTest->buildProductForActivateTest(-1)) && p() && e('0');                                  // 步骤5：激活产品ID为负数