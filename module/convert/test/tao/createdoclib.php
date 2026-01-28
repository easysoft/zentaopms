#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDocLib();
timeout=0
cid=15837

- 步骤1：正常产品文档库创建 @1
- 步骤2：正常项目文档库创建 @1
- 步骤3：正常执行文档库创建 @1
- 步骤4：边界值测试-空名称 @1
- 步骤5：边界值测试-长名称 @1
- 步骤6：特殊字符名称测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-100');
$table->type->range('product,project,execution');
$table->vision->range('rnd');
$table->parent->range('0');
$table->product->range('0-10');
$table->project->range('0-10');
$table->execution->range('0-10');
$table->name->range('测试文档库{0-100}');
$table->baseUrl->range('');
$table->acl->range('default,open');
$table->groups->range('');
$table->users->range('');
$table->main->range('0,1');
$table->collector->range('');
$table->order->range('0-10');
$table->gen(0); // 不生成数据，测试时动态创建

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createDocLibTest(1, 0, 0, '产品文档库', 'product')) && p() && e(1); // 步骤1：正常产品文档库创建
r($convertTest->createDocLibTest(0, 1, 0, '项目文档库', 'project')) && p() && e(1); // 步骤2：正常项目文档库创建
r($convertTest->createDocLibTest(0, 1, 1, '执行文档库', 'execution')) && p() && e(1); // 步骤3：正常执行文档库创建
r($convertTest->createDocLibTest(1, 0, 0, '', 'product')) && p() && e(1); // 步骤4：边界值测试-空名称
r($convertTest->createDocLibTest(1, 0, 0, '这是一个非常长的文档库名称用于测试边界值情况这个名称包含了六十个字符的长度限制测试', 'product')) && p() && e(1); // 步骤5：边界值测试-长名称
r($convertTest->createDocLibTest(1, 0, 0, '测试&<>特殊字符', 'product')) && p() && e(1); // 步骤6：特殊字符名称测试