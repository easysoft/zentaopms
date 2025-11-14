#!/usr/bin/env php
<?php

/**

title=测试 deptModel::fixDeptPath();
timeout=0
cid=15966

- 步骤1：验证fixDeptPath执行成功 @1
- 步骤2：再次验证fixDeptPath执行成功 @1
- 步骤3：验证部门数据总数为10 @10
- 步骤4：再次验证部门数据总数为10 @10
- 步骤5：验证fixDeptPath方法返回值为1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';

// 2. zendata数据准备（从YAML文件加载）
zendata('dept')->loadYaml('dept_fixdeptpath', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$deptTest = new deptTest();

// 5. 执行测试步骤（必须包含至少5个测试步骤）
r($deptTest->fixDeptPathTest()) && p() && e('1'); // 步骤1：验证fixDeptPath执行成功
r($deptTest->fixDeptPathTest()) && p() && e('1'); // 步骤2：再次验证fixDeptPath执行成功  
r($deptTest->fixDeptPathCountTest()) && p() && e('10'); // 步骤3：验证部门数据总数为10
r($deptTest->fixDeptPathCountTest()) && p() && e('10'); // 步骤4：再次验证部门数据总数为10
r($deptTest->fixDeptPathTest()) && p() && e('1'); // 步骤5：验证fixDeptPath方法返回值为1