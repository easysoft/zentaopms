#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::assignForEdit();
timeout=0
cid=19224

- 步骤1：正常情况 @success
- 步骤2：无效产品ID @invalid_product_id
- 步骤3：缺少execution字段 @missing_execution_field
- 步骤4：非对象参数 @invalid_task_object
- 步骤5：无效任务ID @invalid_task_id

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 简化数据准备，只创建基本数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->assignForEditTest()) && p() && e('success'); // 步骤1：正常情况
r($testtaskTest->assignForEditTest(null, 0)) && p() && e('invalid_product_id'); // 步骤2：无效产品ID
r($testtaskTest->assignForEditTest(new stdclass(), 1)) && p() && e('missing_execution_field'); // 步骤3：缺少execution字段
r($testtaskTest->assignForEditTest('not_object', 1)) && p() && e('invalid_task_object'); // 步骤4：非对象参数
r($testtaskTest->assignForEditTest((object)array('id' => 0, 'name' => 'Test', 'execution' => 1, 'project' => 1, 'build' => 'trunk'), 1)) && p() && e('invalid_task_id'); // 步骤5：无效任务ID