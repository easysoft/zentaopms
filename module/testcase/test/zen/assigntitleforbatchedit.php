#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignTitleForBatchEdit();
timeout=0
cid=0

- 步骤1：lib类型用例处理第1条的0属性 @1
- 步骤2：product类型用例处理第0条的0属性 @1
- 步骤3：地盘标签下混合用例第0条的1属性 @1
- 步骤4：空cases数组第0条的0属性 @1
- 步骤5：未知类型处理 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用例对象
$libCases = array();
$productCases = array();
$mixedCases = array();

for($i = 1; $i <= 3; $i++)
{
    $case = new stdClass();
    $case->id = $i;
    $case->product = 0;
    $case->lib = $i;
    $libCases[] = $case;
}

for($i = 4; $i <= 7; $i++)
{
    $case = new stdClass();
    $case->id = $i;
    $case->product = $i - 3;
    $case->lib = 0;
    $productCases[] = $case;
    $mixedCases[] = $case;
}

for($i = 8; $i <= 10; $i++)
{
    $case = new stdClass();
    $case->id = $i;
    $case->product = 0;
    $case->lib = $i - 7;
    $mixedCases[] = $case;
}

r($testcaseTest->assignTitleForBatchEditTest(1, '', 'lib', $libCases)) && p('1:0') && e('1'); // 步骤1：lib类型用例处理
r($testcaseTest->assignTitleForBatchEditTest(1, '', '', $productCases)) && p('0:0') && e('1'); // 步骤2：product类型用例处理
r($testcaseTest->assignTitleForBatchEditTest(0, '', '', $mixedCases)) && p('0:1') && e('1'); // 步骤3：地盘标签下混合用例
r($testcaseTest->assignTitleForBatchEditTest(1, '', '', array())) && p('0:0') && e('1'); // 步骤4：空cases数组
r($testcaseTest->assignTitleForBatchEditTest(0, '', 'unknown', array())) && p('') && e('Array'); // 步骤5：未知类型处理