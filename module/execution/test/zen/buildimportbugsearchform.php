#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildImportBugSearchForm();
timeout=0
cid=0

- 步骤1：正常情况 @0
- 步骤2：无产品情况 @0
- 步骤3：非多项目执行 @0
- 步骤4：多项目执行 @0
- 步骤5：无产品项目 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->project->range('0{3},1{3},2{4}');
$table->name->range('项目1,项目2,项目3,执行1,执行2,执行3,子执行1,子执行2,子执行3,子执行4');
$table->type->range('project{3},sprint{7}');
$table->multiple->range('0{7},1{3}');
$table->hasProduct->range('1{8},0{2}');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->gen(5);

$productPlanTable = zenData('productplan');
$productPlanTable->id->range('1-8');
$productPlanTable->product->range('1{3},2{3},3{2}');
$productPlanTable->title->range('计划1,计划2,计划3,计划4,计划5,计划6,计划7,计划8');
$productPlanTable->gen(8);

$buildTable = zenData('build');
$buildTable->id->range('1-6');
$buildTable->product->range('1{2},2{2},3{2}');
$buildTable->name->range('构建1,构建2,构建3,构建4,构建5,构建6');
$buildTable->gen(6);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1{3},2{3},3{4}');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$moduleTable->type->range('bug');
$moduleTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionZenTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$execution1 = (object)array('id' => 4, 'project' => 1, 'multiple' => 0, 'hasProduct' => 1);
$execution2 = (object)array('id' => 5, 'project' => 1, 'multiple' => 0, 'hasProduct' => 1);
$execution3 = (object)array('id' => 6, 'project' => 2, 'multiple' => 0, 'hasProduct' => 1);
$execution4 = (object)array('id' => 8, 'project' => 1, 'multiple' => 1, 'hasProduct' => 1);
$execution5 = (object)array('id' => 9, 'project' => 3, 'multiple' => 0, 'hasProduct' => 0);

r($executionZenTest->buildImportBugSearchFormTest($execution1, 1, array(1 => '产品1', 2 => '产品2'), array(4 => '执行1'), array(1 => '项目1'))) && p() && e('0'); // 步骤1：正常情况
r($executionZenTest->buildImportBugSearchFormTest($execution2, 2, array(), array(5 => '执行2'), array(1 => '项目1'))) && p() && e('0'); // 步骤2：无产品情况
r($executionZenTest->buildImportBugSearchFormTest($execution3, 3, array(1 => '产品1'), array(6 => '执行3'), array(2 => '项目2'))) && p() && e('0'); // 步骤3：非多项目执行
r($executionZenTest->buildImportBugSearchFormTest($execution4, 4, array(1 => '产品1'), array(8 => '子执行1'), array(1 => '项目1'))) && p() && e('0'); // 步骤4：多项目执行
r($executionZenTest->buildImportBugSearchFormTest($execution5, 5, array(), array(9 => '子执行3'), array(3 => '项目3'))) && p() && e('0'); // 步骤5：无产品项目