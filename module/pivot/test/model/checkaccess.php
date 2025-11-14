#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::checkAccess();
timeout=0
cid=17358

- 步骤1：管理员访问有效pivot - 正常情况 @access_granted
- 步骤2：管理员访问另一个有效pivot - 正常情况 @access_granted
- 步骤3：普通用户访问有效pivot - 权限验证 @access_granted
- 步骤4：普通用户访问有效pivot不同method - 业务规则 @access_granted
- 步骤5：用户访问不存在pivot使用不同method - 边界值异常输入 @access_denied

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 无需zendata数据准备，直接使用模拟权限检查

// 3. 模拟admin用户登录状态
global $app;
$app->user = new stdClass();
$app->user->account = 'admin';
$app->user->admin = 'super';

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->checkAccessTest(1001, 'preview')) && p('') && e('access_granted'); // 步骤1：管理员访问有效pivot - 正常情况
r($pivotTest->checkAccessTest(1002, 'preview')) && p('') && e('access_granted'); // 步骤2：管理员访问另一个有效pivot - 正常情况

// 切换到普通用户user1测试
$app->user->account = 'user1';
$app->user->admin = 'no';
r($pivotTest->checkAccessTest(1003, 'preview')) && p('') && e('access_granted'); // 步骤3：普通用户访问有效pivot - 权限验证
r($pivotTest->checkAccessTest(1004, 'show')) && p('') && e('access_granted'); // 步骤4：普通用户访问有效pivot不同method - 业务规则  
r($pivotTest->checkAccessTest(9999, 'edit')) && p('') && e('access_denied'); // 步骤5：用户访问不存在pivot使用不同method - 边界值异常输入