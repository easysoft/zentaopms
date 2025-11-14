#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getByBuild();
timeout=0
cid=16302

- 步骤1：正常构建ID属性id @1
- 步骤2：不存在的构建ID @0
- 步骤3：构建ID为0 @0
- 步骤4：负数构建ID @0
- 步骤5：非数字构建ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$build = zenData('build');
$build->id->range('1-5');
$build->project->range('1-3');
$build->product->range('1-2');
$build->branch->range('0');
$build->execution->range('1-3');
$build->name->range('v1.0.1,v1.0.2,v1.0.3,v1.0.4,v1.0.5');
$build->date->range('`2023-01-01`');
$build->builder->range('admin');
$build->deleted->range('0');
$build->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('执行1,执行2,执行3,执行4,执行5');
$project->type->range('sprint');
$project->status->range('wait,doing');
$project->deleted->range('0');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->getByBuildTest(1)) && p('id') && e('1'); // 步骤1：正常构建ID
r($executionTest->getByBuildTest(999)) && p() && e('0'); // 步骤2：不存在的构建ID
r($executionTest->getByBuildTest(0)) && p() && e('0'); // 步骤3：构建ID为0
r($executionTest->getByBuildTest(-1)) && p() && e('0'); // 步骤4：负数构建ID
r($executionTest->getByBuildTest('abc')) && p() && e('0'); // 步骤5：非数字构建ID