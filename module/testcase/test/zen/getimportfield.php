#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getImportField();
timeout=0
cid=0

- 执行$result1->story @123
- 执行$result2->module @456
- 执行$result3->branch @789
- 执行$result4->story @0
- 执行$result5->steps @Test step description
- 执行$result6->expects @Expected result
- 执行$result7->title @Test Case Title

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1: 测试story字段提取ID,从"Story (#123)"中提取ID为123
$case1 = new stdclass();
$result1 = $testcaseTest->getImportFieldTest('story', 'Story Title (#123)', $case1);
r($result1->story) && p() && e('123');

// 步骤2: 测试module字段提取ID,从"Module (#456)"中提取ID为456
$case2 = new stdclass();
$result2 = $testcaseTest->getImportFieldTest('module', 'Module Name (#456)', $case2);
r($result2->module) && p() && e('456');

// 步骤3: 测试branch字段提取ID,从"Branch (#789)"中提取ID为789
$case3 = new stdclass();
$result3 = $testcaseTest->getImportFieldTest('branch', 'Branch Name (#789)', $case3);
r($result3->branch) && p() && e('789');

// 步骤4: 测试story字段无ID格式时设置为0
$case4 = new stdclass();
$result4 = $testcaseTest->getImportFieldTest('story', 'Story Title Without ID', $case4);
r($result4->story) && p() && e('0');

// 步骤5: 测试stepDesc字段映射到steps属性
$case5 = new stdclass();
$result5 = $testcaseTest->getImportFieldTest('stepDesc', 'Test step description', $case5);
r($result5->steps) && p() && e('Test step description');

// 步骤6: 测试stepExpect字段映射到expects属性
$case6 = new stdclass();
$result6 = $testcaseTest->getImportFieldTest('stepExpect', 'Expected result', $case6);
r($result6->expects) && p() && e('Expected result');

// 步骤7: 测试普通字段直接赋值,如title字段
$case7 = new stdclass();
$result7 = $testcaseTest->getImportFieldTest('title', 'Test Case Title', $case7);
r($result7->title) && p() && e('Test Case Title');