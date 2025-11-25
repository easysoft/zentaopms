#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildWorkflowSchemeData();
timeout=0
cid=15829

- 步骤1：完整有效数据
 - 属性id @1
 - 属性name @Test Workflow
 - 属性description @This is a test workflow scheme
- 步骤2：只包含必需字段属性description @~~
- 步骤3：空description属性description @~~
- 步骤4：特殊字符
 - 属性id @4
 - 属性name @SpecialWorkflow
 - 属性description @Contains special chars
- 步骤5：中文字符
 - 属性id @5
 - 属性name @中文工作流
 - 属性description @这是一个中文描述的工作流方案

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildWorkflowSchemeDataTest(array('id' => 1, 'name' => 'Test Workflow', 'description' => 'This is a test workflow scheme'))) && p('id,name,description') && e('1,Test Workflow,This is a test workflow scheme'); // 步骤1：完整有效数据
r($convertTest->buildWorkflowSchemeDataTest(array('id' => 2, 'name' => 'Basic Workflow'))) && p('description') && e('~~'); // 步骤2：只包含必需字段
r($convertTest->buildWorkflowSchemeDataTest(array('id' => 3, 'name' => 'Empty Description Workflow', 'description' => ''))) && p('description') && e('~~'); // 步骤3：空description
r($convertTest->buildWorkflowSchemeDataTest(array('id' => 4, 'name' => 'SpecialWorkflow', 'description' => 'Contains special chars'))) && p('id,name,description') && e('4,SpecialWorkflow,Contains special chars'); // 步骤4：特殊字符
r($convertTest->buildWorkflowSchemeDataTest(array('id' => 5, 'name' => '中文工作流', 'description' => '这是一个中文描述的工作流方案'))) && p('id,name,description') && e('5,中文工作流,这是一个中文描述的工作流方案'); // 步骤5：中文字符