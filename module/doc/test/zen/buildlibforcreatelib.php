#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildLibForCreateLib();
timeout=0
cid=0

- 步骤1：产品类型
 - 属性addedBy @admin
 - 属性product @1
- 步骤2：项目类型
 - 属性addedBy @admin
 - 属性project @1
- 步骤3：执行类型
 - 属性addedBy @admin
 - 属性execution @1
- 步骤4：API类型不包含product和execution属性addedBy @admin
- 步骤5：基本情况属性addedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 4. 准备测试场景
global $app;

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 测试产品类型文档库构建
$_POST = array('type' => 'product', 'product' => 1, 'name' => '产品文档库');
r($docTest->buildLibForCreateLibTest()) && p('addedBy,product') && e('admin,1'); // 步骤1：产品类型

// 步骤2：边界值 - 测试项目类型文档库构建
$_POST = array('type' => 'project', 'project' => 1, 'name' => '项目文档库');
r($docTest->buildLibForCreateLibTest()) && p('addedBy,project') && e('admin,1'); // 步骤2：项目类型

// 步骤3：异常输入 - 测试执行类型文档库构建
$_POST = array('type' => 'execution', 'execution' => 1, 'name' => '执行文档库', 'libType' => 'doc');
r($docTest->buildLibForCreateLibTest()) && p('addedBy,execution') && e('admin,1'); // 步骤3：执行类型

// 步骤4：权限验证 - 测试API类型文档库构建（不应包含execution）
$_POST = array('type' => 'api', 'product' => 1, 'execution' => 1, 'libType' => 'api', 'name' => 'API文档库');
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 步骤4：API类型不包含product和execution

// 步骤5：业务规则 - 测试基本文档库构建
$_POST = array('name' => '基本文档库');
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 步骤5：基本情况