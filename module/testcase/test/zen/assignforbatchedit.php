#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForBatchEdit();
timeout=0
cid=0

- 步骤1：测试普通产品批量编辑用例
 - 属性products @1
 - 属性branchProduct @0
- 步骤2：测试分支产品批量编辑用例
 - 属性products @1
 - 属性branchProduct @1
- 步骤3：测试用例库批量编辑属性libID @1
- 步骤4：测试空用例数组边界情况属性products @1
- 步骤5：测试多个用例批量编辑
 - 属性products @1
 - 属性customFields @8
- 步骤6：测试无效产品ID属性products @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-20');
$table->product->range('1{10},2{5},3{5}');
$table->branch->range('0{10},1{5},2{5}');
$table->lib->range('0{15},1{5}');
$table->module->range('1-5');
$table->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$table->type->range('feature{10},performance{5},config{3},install{2}');
$table->pri->range('1-4');
$table->status->range('normal{15},blocked{3},investigate{2}');
$table->stage->range('unittest{5},feature{5},intergrate{5},system{3},smoke{2}');
$table->openedBy->range('admin{10},user1{5},tester{5}');
$table->openedDate->range('`2024-01-01 10:00:00`');
$table->deleted->range('0{18},1{2}');
$table->gen(20);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{2},branch{2},platform{1}');
$productTable->status->range('normal');
$productTable->createdBy->range('admin');
$productTable->createdDate->range('`2024-01-01 10:00:00`');
$productTable->deleted->range('0');
$productTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('2{3},3{2}');
$branchTable->name->range('分支1,分支2,分支3,分支4,分支5');
$branchTable->status->range('active{4},closed{1}');
$branchTable->deleted->range('0');
$branchTable->gen(5);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1{5},2{3},3{2}');
$moduleTable->branch->range('0{5},1{3},2{2}');
$moduleTable->name->range('模块A,模块B,模块C,模块D,模块E');
$moduleTable->type->range('case');
$moduleTable->parent->range('0');
$moduleTable->grade->range('1');
$moduleTable->deleted->range('0');
$moduleTable->gen(10);

$libTable = zenData('testsuite');
$libTable->id->range('1-5');
$libTable->name->range('测试库A,测试库B,测试库C,测试库D,测试库E');
$libTable->type->range('library');
$libTable->product->range('0');
$libTable->deleted->range('0');
$libTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignForBatchEditTest(1, '', 'case', array(1, 2, 3))) && p('products,branchProduct') && e('1,0'); // 步骤1：测试普通产品批量编辑用例
r($testcaseTest->assignForBatchEditTest(3, '1', 'case', array(11, 12, 13))) && p('products,branchProduct') && e('1,1'); // 步骤2：测试分支产品批量编辑用例
r($testcaseTest->assignForBatchEditTest(1, '', 'lib', array(16, 17, 18))) && p('libID') && e('1'); // 步骤3：测试用例库批量编辑
r($testcaseTest->assignForBatchEditTest(1, '', 'case', array())) && p('products') && e('1'); // 步骤4：测试空用例数组边界情况
r($testcaseTest->assignForBatchEditTest(1, '', 'case', array(1, 2, 3, 4, 5))) && p('products,customFields') && e('1,8'); // 步骤5：测试多个用例批量编辑
r($testcaseTest->assignForBatchEditTest(999, '', 'case', array(1, 2, 3))) && p('products') && e('0'); // 步骤6：测试无效产品ID