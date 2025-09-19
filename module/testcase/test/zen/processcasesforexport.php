#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processCasesForExport();
timeout=0
cid=0

- 步骤1：正常情况属性error @~~
- 步骤2：空数组属性error @~~
- 步骤3：带测试任务属性error @~~
- 步骤4：多个用例属性error @~~
- 步骤5：无效产品ID属性error @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-3');
$case->product->range('1{2},2{1}');
$case->title->range('测试用例1,测试用例2,测试用例3');
$case->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 构造测试用例对象
$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->branch = 0;
$case1->module = 1;
$case1->story = 1;
$case1->scene = 1;
$case1->title = '测试用例1';
$case1->pri = 2;
$case1->type = 'feature';
$case1->status = 'normal';
$case1->openedBy = 'admin';
$case1->openedDate = '2023-01-01 00:00:00';
$case1->lastEditedBy = 'admin';
$case1->lastEditedDate = '2023-01-15 00:00:00';
$case1->lastRunner = 'admin';
$case1->lastRunDate = '2023-02-01 00:00:00';
$case1->lastRunResult = 'pass';
$case1->linkCase = '';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processCasesForExportTest(array(1 => $case1), 1, 0)) && p('error') && e('~~'); // 步骤1：正常情况
r($testcaseTest->processCasesForExportTest(array(), 1, 0)) && p('error') && e('~~'); // 步骤2：空数组
r($testcaseTest->processCasesForExportTest(array(1 => $case1), 1, 1)) && p('error') && e('~~'); // 步骤3：带测试任务
r($testcaseTest->processCasesForExportTest(array(1 => $case1, 2 => $case1), 2, 0)) && p('error') && e('~~'); // 步骤4：多个用例
r($testcaseTest->processCasesForExportTest(array(1 => $case1), 999, 0)) && p('error') && e('~~'); // 步骤5：无效产品ID