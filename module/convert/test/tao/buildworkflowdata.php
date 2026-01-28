#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildWorkflowData();
timeout=0
cid=15828

- 步骤1：正常情况-验证id属性id @1001
- 步骤2：正常情况-验证workflowname属性workflowname @Default Workflow
- 步骤3：正常情况-验证descriptor属性descriptor @This is the default workflow
- 步骤4：字段值为空-验证空id属性id @~~
- 步骤5：包含额外字段-验证只提取需要的字段属性id @1003

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildWorkflowDataTest(array('id' => '1001', 'name' => 'Default Workflow', 'descriptor' => 'This is the default workflow'))) && p('id') && e('1001'); // 步骤1：正常情况-验证id
r($convertTest->buildWorkflowDataTest(array('id' => '1001', 'name' => 'Default Workflow', 'descriptor' => 'This is the default workflow'))) && p('workflowname') && e('Default Workflow'); // 步骤2：正常情况-验证workflowname
r($convertTest->buildWorkflowDataTest(array('id' => '1001', 'name' => 'Default Workflow', 'descriptor' => 'This is the default workflow'))) && p('descriptor') && e('This is the default workflow'); // 步骤3：正常情况-验证descriptor
r($convertTest->buildWorkflowDataTest(array('id' => '', 'name' => '', 'descriptor' => ''))) && p('id') && e('~~'); // 步骤4：字段值为空-验证空id
r($convertTest->buildWorkflowDataTest(array('id' => '1003', 'name' => 'Test Workflow', 'descriptor' => 'Test description', 'extra_field' => 'should_be_ignored'))) && p('id') && e('1003'); // 步骤5：包含额外字段-验证只提取需要的字段