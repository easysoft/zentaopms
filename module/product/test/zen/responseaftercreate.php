#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseAfterCreate();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性result @success
 - 属性message @保存成功
- 步骤2：JSON视图
 - 属性result @success
 - 属性id @2
- 步骤3：钩子消息
 - 属性result @success
 - 属性message @hook message
- 步骤4：无效产品ID属性result @success
- 步骤5：无项目集ID属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('1-3');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->code->range('PRD001-PRD010');
$table->status->range('normal{8},closed{2}');
$table->type->range('normal{10}');
$table->gen(10);

$programTable = zenData('project');
$programTable->id->range('1-3');
$programTable->name->range('项目集1,项目集2,项目集3');
$programTable->type->range('program{3}');
$programTable->status->range('wait{3}');
$programTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->responseAfterCreateTest(1, 1)) && p('result,message') && e('success,保存成功'); // 步骤1：正常情况
r($productTest->responseAfterCreateTest(2, 2, 'json')) && p('result,id') && e('success,2'); // 步骤2：JSON视图
r($productTest->responseAfterCreateTest(3, 3, '', 'hook message')) && p('result,message') && e('success,hook message'); // 步骤3：钩子消息
r($productTest->responseAfterCreateTest(999, 1)) && p('result') && e('success'); // 步骤4：无效产品ID
r($productTest->responseAfterCreateTest(4, 0)) && p('result') && e('success'); // 步骤5：无项目集ID