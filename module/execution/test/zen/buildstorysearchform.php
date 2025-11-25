#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildStorySearchForm();
timeout=0
cid=16418

- 步骤1：正常执行和查询ID属性success @1
- 步骤2：第二个执行和产品ID属性success @1
- 步骤3：第三个执行和产品ID属性success @1
- 步骤4：不同查询ID属性queryID @2
- 步骤5：无效执行ID属性success @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$execution = zenData('project');
$execution->type->range('execution');
$execution->name->range('执行1,执行2,执行3');
$execution->status->range('wait{3}');
$execution->gen(3);

$product = zenData('product');
$product->name->range('产品1,产品2,产品3');
$product->status->range('normal{3}');
$product->code->range('prod1,prod2,prod3');
$product->gen(3);

$module = zenData('module');
$module->root->range('1-3:3,1-3:3');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6');
$module->type->range('story{6}');
$module->parent->range('0{3},1-3');
$module->path->range(',1,,2,,3,');
$module->grade->range('1{3},2{3}');
$module->gen(6);

$branch = zenData('branch');
$branch->product->range('1-3');
$branch->name->range('分支1,分支2,分支3');
$branch->status->range('active{3}');
$branch->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionZenTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionZenTest->buildStorySearchFormTest(1, 1, 1)) && p('success') && e('1'); // 步骤1：正常执行和查询ID
r($executionZenTest->buildStorySearchFormTest(2, 1, 1)) && p('success') && e('1'); // 步骤2：第二个执行和产品ID
r($executionZenTest->buildStorySearchFormTest(3, 2, 1)) && p('success') && e('1'); // 步骤3：第三个执行和产品ID
r($executionZenTest->buildStorySearchFormTest(1, 1, 2)) && p('queryID') && e('2'); // 步骤4：不同查询ID
r($executionZenTest->buildStorySearchFormTest(0, 1, 1)) && p('success') && e('~~'); // 步骤5：无效执行ID