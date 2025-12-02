#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::processStepsChanged();
timeout=0
cid=19017

- 步骤1：相同内容 @0
- 步骤2：不同数量 @1
- 步骤3：不同描述 @1
- 步骤4：不同期望 @1
- 步骤5：不同类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('casestep');
$table->id->range('1-10');
$table->case->range('1-3');
$table->version->range('1');
$table->type->range('step,group,item');
$table->desc->range('步骤描述1,步骤描述2,步骤描述3');
$table->expect->range('期望结果1,期望结果2,期望结果3');
$table->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processStepsChangedTest('same_content')) && p() && e('0'); // 步骤1：相同内容
r($testcaseTest->processStepsChangedTest('different_count')) && p() && e('1'); // 步骤2：不同数量
r($testcaseTest->processStepsChangedTest('different_desc')) && p() && e('1'); // 步骤3：不同描述
r($testcaseTest->processStepsChangedTest('different_expect')) && p() && e('1'); // 步骤4：不同期望
r($testcaseTest->processStepsChangedTest('different_type')) && p() && e('1'); // 步骤5：不同类型