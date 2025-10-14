#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Activate();
timeout=0
cid=0

- 步骤1：基本表单字段结构验证 - 返回2个字段 @2
- 步骤2：status字段类型验证第status条的type属性 @string
- 步骤3：status字段控件验证第status条的control属性 @hidden
- 步骤4：status字段默认值验证第status条的default属性 @normal
- 步骤5：comment字段控件验证第comment条的control属性 @editor

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// getFormFields4Activate方法不需要数据库数据，主要是返回配置数组

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($productTest->getFormFields4ActivateTest())) && p() && e('2'); // 步骤1：基本表单字段结构验证 - 返回2个字段
r($productTest->getFormFields4ActivateTest()) && p('status:type') && e('string'); // 步骤2：status字段类型验证
r($productTest->getFormFields4ActivateTest()) && p('status:control') && e('hidden'); // 步骤3：status字段控件验证
r($productTest->getFormFields4ActivateTest()) && p('status:default') && e('normal'); // 步骤4：status字段默认值验证
r($productTest->getFormFields4ActivateTest()) && p('comment:control') && e('editor'); // 步骤5：comment字段控件验证