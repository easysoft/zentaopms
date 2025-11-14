#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignTestcaseVars();
timeout=0
cid=16408

- 步骤1：正常情况
 - 属性executionID @1
 - 属性productID @1
 - 属性type @all
- 步骤2：无效执行ID属性recTotal @0
- 步骤3：无效产品ID属性product @~~
- 步骤4：指定模块ID
 - 属性moduleID @1
 - 属性moduleName @模块1
- 步骤5：分页参数测试
 - 属性branchID @1
 - 属性recTotal @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$execution = zenData('project');
$execution->id->range('1-3');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('sprint');
$execution->status->range('wait');
$execution->gen(3);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1,产品2');
$product->type->range('normal');
$product->gen(2);

$testcase = zenData('case');
$testcase->id->range('1-5');
$testcase->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$testcase->status->range('normal{3},wait{2}');
$testcase->lastRunner->range('admin{5}');
$testcase->lastRunResult->range('pass{3},fail{2}');
$testcase->story->range('1-5');
$testcase->module->range('1{5}');
$testcase->gen(5);

$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->deleted->range('0{3}');
$user->gen(3);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('1{2}');
$branch->name->range('分支1,分支2');
$branch->deleted->range('0{2}');
$branch->gen(2);

$module = zenData('module');
$module->id->range('1-3');
$module->type->range('case{3}');
$module->name->range('模块1,模块2,模块3');
$module->deleted->range('0{3}');
$module->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建模拟分页对象
$pager = new stdClass();
$pager->recTotal = 3;
$pager->pageID = 1;

r($executionTest->assignTestcaseVarsTest(1, 1, '0', 0, 0, 'id_desc', 'all', $pager)) && p('executionID,productID,type') && e('1,1,all'); // 步骤1：正常情况
r($executionTest->assignTestcaseVarsTest(0, 1, '0', 0, 0, 'id_desc', 'all', $pager)) && p('recTotal') && e('0'); // 步骤2：无效执行ID
r($executionTest->assignTestcaseVarsTest(1, 0, '0', 0, 0, 'id_desc', 'all', $pager)) && p('product') && e('~~'); // 步骤3：无效产品ID
r($executionTest->assignTestcaseVarsTest(1, 1, '0', 1, 0, 'id_desc', 'bymodule', $pager)) && p('moduleID,moduleName') && e('1,模块1'); // 步骤4：指定模块ID
r($executionTest->assignTestcaseVarsTest(1, 1, '1', 0, 0, 'id_desc', 'all', $pager)) && p('branchID,recTotal') && e('1,3'); // 步骤5：分页参数测试