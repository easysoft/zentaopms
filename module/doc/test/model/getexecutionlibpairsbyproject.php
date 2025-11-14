#!/usr/bin/env php
<?php

/**

title=测试 docModel::getExecutionLibPairsByProject();
timeout=0
cid=16091

- 步骤1：测试项目ID=1的执行文档库键值对数量 @3
- 步骤2：测试带withObject参数的执行文档库名称格式化属性1 @Sprint1 / 执行文档库1
- 步骤3：测试不存在执行文档库的项目ID=999数量 @2
- 步骤4：测试无效项目ID=0数量 @0
- 步骤5：测试项目ID=2的执行文档库权限过滤数量 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('execution{3},project{2},execution{2},custom{3}');
$table->vision->range('rnd');
$table->project->range('1{3},2{2},999{2},0{3}');
$table->execution->range('101{2},102{1},103{1},104{1},0{5}');
$table->name->range('执行文档库1,执行文档库2,执行文档库3,项目文档库1,项目文档库2,执行文档库4,执行文档库5,自定义库1,自定义库2,自定义库3');
$table->deleted->range('0{9},1{1}');
$table->acl->range('open');
$table->gen(10);

$executionTable = zenData('project');
$executionTable->id->range('101-105');
$executionTable->name->range('Sprint1,Sprint2,Sprint3,Sprint4,Sprint5');
$executionTable->type->range('sprint');
$executionTable->project->range('1{3},2{2}');
$executionTable->deleted->range('0');
$executionTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($docTest->getExecutionLibPairsByProjectTest(1))) && p() && e('3'); // 步骤1：测试项目ID=1的执行文档库键值对数量
r($docTest->getExecutionLibPairsByProjectTest(1, 'withObject', array('101' => 'Sprint1', '102' => 'Sprint2'))) && p('1') && e('Sprint1 / 执行文档库1'); // 步骤2：测试带withObject参数的执行文档库名称格式化
r(count($docTest->getExecutionLibPairsByProjectTest(999))) && p() && e('2'); // 步骤3：测试不存在执行文档库的项目ID=999数量
r(count($docTest->getExecutionLibPairsByProjectTest(0))) && p() && e('0'); // 步骤4：测试无效项目ID=0数量
r(count($docTest->getExecutionLibPairsByProjectTest(2))) && p() && e('0'); // 步骤5：测试项目ID=2的执行文档库权限过滤数量