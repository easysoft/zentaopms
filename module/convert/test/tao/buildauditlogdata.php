#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildAuditLogData();
timeout=0
cid=15801

- 步骤1：完整数据输入id字段属性id @1
- 步骤2：部分字段缺失时object_type字段属性object_type @~~
- 步骤3：仅包含必需id字段时summary属性summary @~~
- 步骤4：objectId字段数值转换属性object_id @200
- 步骤5：包含额外字段忽略处理属性summary @Extra fields test

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildAuditLogDataTest(array('id' => 1, 'summary' => 'Test audit log', 'objectType' => 'task', 'objectId' => 100))) && p('id') && e('1'); // 步骤1：完整数据输入id字段
r($convertTest->buildAuditLogDataTest(array('id' => 2, 'summary' => 'Partial data'))) && p('object_type') && e('~~'); // 步骤2：部分字段缺失时object_type字段
r($convertTest->buildAuditLogDataTest(array('id' => 0))) && p('summary') && e('~~'); // 步骤3：仅包含必需id字段时summary
r($convertTest->buildAuditLogDataTest(array('id' => 3, 'objectId' => 200))) && p('object_id') && e('200'); // 步骤4：objectId字段数值转换
r($convertTest->buildAuditLogDataTest(array('id' => 4, 'summary' => 'Extra fields test', 'objectType' => 'bug', 'objectId' => 200, 'extraField' => 'ignored'))) && p('summary') && e('Extra fields test'); // 步骤5：包含额外字段忽略处理