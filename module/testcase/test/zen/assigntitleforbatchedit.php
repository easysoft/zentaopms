#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignTitleForBatchEdit();
timeout=0
cid=0

- 步骤1：测试type为lib时属性libIdListCount @1
- 步骤2：测试type为case且productID有效时属性productIdListCount @1
- 步骤3：测试从cases中提取productIdList属性productIdListCount @2
- 步骤4：测试从cases中提取libIdList属性libIdListCount @2
- 步骤5：测试混合用例
 - 属性productIdListCount @1
 - 属性libIdListCount @1
- 步骤6：测试空cases数组边界情况属性productIdListCount @1
- 步骤7：测试带分支的产品批量编辑属性productIdListCount @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-20');
$caseTable->product->range('1{5},2{5},3{5},4{5}');
$caseTable->branch->range('0{10},1{5},2{5}');
$caseTable->lib->range('0{10},1{5},2{5}');
$caseTable->module->range('1-5');
$caseTable->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$caseTable->type->range('feature{10},performance{5},config{3},install{2}');
$caseTable->status->range('normal{15},blocked{3},investigate{2}');
$caseTable->deleted->range('0');
$caseTable->gen(20);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{3},branch{2}');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(5);

$libTable = zenData('testsuite');
$libTable->id->range('1-5');
$libTable->name->range('测试库A,测试库B,测试库C,测试库D,测试库E');
$libTable->type->range('library');
$libTable->product->range('0');
$libTable->deleted->range('0');
$libTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('4{3},5{2}');
$branchTable->name->range('分支1,分支2,分支3,分支4,分支5');
$branchTable->status->range('active');
$branchTable->deleted->range('0');
$branchTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 准备测试用例数据
$productCase1 = new stdClass();
$productCase1->id = 1;
$productCase1->product = 1;
$productCase1->lib = 0;

$productCase2 = new stdClass();
$productCase2->id = 2;
$productCase2->product = 2;
$productCase2->lib = 0;

$libCase1 = new stdClass();
$libCase1->id = 11;
$libCase1->product = 0;
$libCase1->lib = 1;

$libCase2 = new stdClass();
$libCase2->id = 12;
$libCase2->product = 0;
$libCase2->lib = 2;

$mixedCases = array($productCase1, $libCase1);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignTitleForBatchEditTest(1, '', 'lib', array())) && p('libIdListCount') && e('1'); // 步骤1：测试type为lib时
r($testcaseTest->assignTitleForBatchEditTest(1, '', 'case', array())) && p('productIdListCount') && e('1'); // 步骤2：测试type为case且productID有效时
r($testcaseTest->assignTitleForBatchEditTest(0, '', 'case', array($productCase1, $productCase2))) && p('productIdListCount') && e('2'); // 步骤3：测试从cases中提取productIdList
r($testcaseTest->assignTitleForBatchEditTest(0, '', 'case', array($libCase1, $libCase2))) && p('libIdListCount') && e('2'); // 步骤4：测试从cases中提取libIdList
r($testcaseTest->assignTitleForBatchEditTest(0, '', 'case', $mixedCases)) && p('productIdListCount,libIdListCount') && e('1,1'); // 步骤5：测试混合用例
r($testcaseTest->assignTitleForBatchEditTest(2, '', 'case', array())) && p('productIdListCount') && e('1'); // 步骤6：测试空cases数组边界情况
r($testcaseTest->assignTitleForBatchEditTest(4, '1', 'case', array())) && p('productIdListCount') && e('1'); // 步骤7：测试带分支的产品批量编辑