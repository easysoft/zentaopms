#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildCasesForBathcEdit();
timeout=0
cid=19078

- 步骤1：空用例数据测试 @0
- 步骤2：测试返回数组长度 @3
- 步骤3：测试用例1存在 @1
- 步骤4：测试用例2存在 @1
- 步骤5：测试用例3存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-5');
$case->product->range('1');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->precondition->range('前置条件1,前置条件2,前置条件3,前置条件4,前置条件5');
$case->version->range('1');
$case->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcasezenTest();

// 准备简单的测试数据
$emptyOldCases = array();
$emptyOldSteps = array();

$oldCases = array(
    1 => (object)array(
        'id' => 1,
        'product' => 1,
        'title' => '测试用例1',
        'precondition' => '前置条件1',
        'version' => 1
    ),
    2 => (object)array(
        'id' => 2,
        'product' => 1,
        'title' => '测试用例2',
        'precondition' => '前置条件2',
        'version' => 1
    ),
    3 => (object)array(
        'id' => 3,
        'product' => 1,
        'title' => '测试用例3',
        'precondition' => '前置条件3',
        'version' => 1
    )
);

$oldSteps = array(
    1 => array(),
    2 => array(),
    3 => array()
);

// 模拟简单的POST数据
$_POST = array(
    'title' => array(
        1 => '测试用例1',
        2 => '测试用例2',
        3 => '测试用例3'
    )
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($testcaseTest->buildCasesForBathcEditTest($emptyOldCases, $emptyOldSteps))) && p() && e('0'); // 步骤1：空用例数据测试
r(count($testcaseTest->buildCasesForBathcEditTest($oldCases, $oldSteps))) && p() && e('3'); // 步骤2：测试返回数组长度
r(array_key_exists(1, $testcaseTest->buildCasesForBathcEditTest($oldCases, $oldSteps))) && p() && e('1'); // 步骤3：测试用例1存在
r(array_key_exists(2, $testcaseTest->buildCasesForBathcEditTest($oldCases, $oldSteps))) && p() && e('1'); // 步骤4：测试用例2存在
r(array_key_exists(3, $testcaseTest->buildCasesForBathcEditTest($oldCases, $oldSteps))) && p() && e('1'); // 步骤5：测试用例3存在