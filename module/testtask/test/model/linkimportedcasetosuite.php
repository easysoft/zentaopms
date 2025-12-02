#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::linkImportedCaseToSuite();
timeout=0
cid=19209

- 步骤1：正常关联 @1
- 步骤2：空对象但有必需属性 @1
- 步骤3：无效ID仍可执行replace操作 @1
- 步骤4：空套件对象但有必需属性 @1
- 步骤5：重复关联，replace操作正常 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备
$case = zenData('case');
$case->id->range('1-10');
$case->title->range('测试用例{1-10}');
$case->product->range('1-3');
$case->version->range('1-2');
$case->gen(10);

$suite = zenData('testsuite');
$suite->id->range('1-5');
$suite->name->range('测试套件{1-5}');
$suite->product->range('1-3');
$suite->gen(5);

$suitecase = zenData('suitecase');
$suitecase->suite->range('1-5');
$suitecase->case->range('1-10');
$suitecase->product->range('1-3');
$suitecase->version->range('1-2');
$suitecase->gen(0); // 先清空现有数据

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskTest();

// 5. 测试步骤：必须包含至少5个测试步骤
// 构造测试数据对象
$testCase = new stdClass();
$testCase->id = 1;
$testCase->title = '测试用例1';
$testCase->product = 1;
$testCase->version = 1;

$testSuiteCase = new stdClass();
$testSuiteCase->suite = 1;
$testSuiteCase->case = 0; // 将由方法设置
$testSuiteCase->product = 0; // 将由方法设置
$testSuiteCase->version = 0; // 将由方法设置

r($testtaskTest->linkImportedCaseToSuiteTest($testCase, 1, $testSuiteCase)) && p() && e('1'); // 步骤1：正常关联

// 步骤2：空测试用例对象关联（但包含必需属性）
$emptyCase = new stdClass();
$emptyCase->version = 1;
$emptyCase->product = 1;
r($testtaskTest->linkImportedCaseToSuiteTest($emptyCase, 2, $testSuiteCase)) && p() && e('1'); // 步骤2：空对象但有必需属性

// 步骤3：无效测试用例ID关联
$testCase->id = 999; // 不存在的ID
r($testtaskTest->linkImportedCaseToSuiteTest($testCase, 999, $testSuiteCase)) && p() && e('1'); // 步骤3：无效ID仍可执行replace操作

// 步骤4：空测试套件用例对象关联（但包含必需属性）
$emptySuiteCase = new stdClass();
$emptySuiteCase->suite = 2;
$emptySuiteCase->case = 0;
$emptySuiteCase->product = 0;
$emptySuiteCase->version = 0;
r($testtaskTest->linkImportedCaseToSuiteTest($testCase, 3, $emptySuiteCase)) && p() && e('1'); // 步骤4：空套件对象但有必需属性

// 步骤5：重复关联相同测试用例到同一套件
$testCase->id = 1;
$testSuiteCase->suite = 1;
r($testtaskTest->linkImportedCaseToSuiteTest($testCase, 1, $testSuiteCase)) && p() && e('1'); // 步骤5：重复关联，replace操作正常