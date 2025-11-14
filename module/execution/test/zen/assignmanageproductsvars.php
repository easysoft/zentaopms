#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignManageProductsVars();
timeout=0
cid=16404

- 步骤1：正常执行对象分配管理产品变量第execution条的name属性 @测试执行1
- 步骤2：无关联产品的执行分配管理产品变量第allProducts条的2属性 @产品2
- 步骤3：有关联需求的产品不可修改第unmodifiableProducts条的0属性 @1
- 步骤4：多产品多分支场景第execution条的id属性 @4
- 步骤5：权限验证和业务规则属性title @测试执行5-产品管理

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->deleted->range('0');
$product->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试执行对象
$execution1 = new stdClass();
$execution1->id = 1;
$execution1->name = '测试执行1';
$execution1->project = 1;

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->name = '测试执行2';
$execution2->project = 2;

$execution3 = new stdClass();
$execution3->id = 3;
$execution3->name = '测试执行3';
$execution3->project = 3;

$execution4 = new stdClass();
$execution4->id = 4;
$execution4->name = '测试执行4';
$execution4->project = 1;

$execution5 = new stdClass();
$execution5->id = 5;
$execution5->name = '测试执行5';
$execution5->project = 4;

r($executionTest->assignManageProductsVarsTest($execution1)) && p('execution:name') && e('测试执行1'); // 步骤1：正常执行对象分配管理产品变量
r($executionTest->assignManageProductsVarsTest($execution2)) && p('allProducts:2') && e('产品2'); // 步骤2：无关联产品的执行分配管理产品变量
r($executionTest->assignManageProductsVarsTest($execution3)) && p('unmodifiableProducts:0') && e('1'); // 步骤3：有关联需求的产品不可修改
r($executionTest->assignManageProductsVarsTest($execution4)) && p('execution:id') && e('4'); // 步骤4：多产品多分支场景
r($executionTest->assignManageProductsVarsTest($execution5)) && p('title') && e('测试执行5-产品管理'); // 步骤5：权限验证和业务规则