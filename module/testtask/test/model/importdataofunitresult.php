#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::importDataOfUnitResult();
timeout=0
cid=19199

- 步骤1：空数据导入成功 @1
- 步骤2：空数据导入成功 @1
- 步骤3：空数据导入成功 @1
- 步骤4：空数据导入成功 @1
- 步骤5：不同自动化类型空数据导入 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$testtaskTable = zenData('testtask');
$testtaskTable->id->range('1-10');
$testtaskTable->name->range('Unit Test Task{3}, Function Test Task{3}, API Test Task{2}, Performance Test Task{2}');
$testtaskTable->product->range('1-3');
$testtaskTable->project->range('1-5');
$testtaskTable->status->range('wait{3},doing{4},done{2},blocked{1}');
$testtaskTable->owner->range('admin{5},user1{3},user2{2}');
$testtaskTable->gen(10);

$testsuiteTable = zenData('testsuite');
$testsuiteTable->id->range('1-20');
$testsuiteTable->product->range('1-3');
$testsuiteTable->name->range('UnitTestSuite{10}, FuncTestSuite{6}, ApiTestSuite{4}');
$testsuiteTable->type->range('unit{10}, func{6}, api{4}');
$testsuiteTable->deleted->range('0{18}, 1{2}');
$testsuiteTable->gen(20);

$testcaseTable = zenData('case');
$testcaseTable->id->range('1-50');
$testcaseTable->product->range('1-3');
$testcaseTable->title->range('Test Case{50}');
$testcaseTable->type->range('feature{30}, performance{10}, config{10}');
$testcaseTable->auto->range('unit{20}, func{15}, no{15}');
$testcaseTable->status->range('normal{40}, blocked{5}, investigate{5}');
$testcaseTable->openedBy->range('admin{30}, user1{10}, user2{10}');
$testcaseTable->deleted->range('0{45}, 1{5}');
$testcaseTable->gen(50);

$suiteTable = zenData('suitecase');
$suiteTable->suite->range('1-10');
$suiteTable->product->range('1-3');
$suiteTable->case->range('1-50');
$suiteTable->version->range('1');
$suiteTable->gen(30);

// 清空action表以避免数据库约束问题
zenData('action')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskModelTest();

// 准备测试数据
// 测试步骤1：空数据测试（最简单的情况）
$suites1 = array();
$cases1 = array();
$results1 = array();
$suiteNames1 = array();
$caseTitles1 = array();

// 测试步骤2：空套件和用例数据导入
$suites2 = array();
$cases2 = array();
$results2 = array();
$suiteNames2 = array();
$caseTitles2 = array();

// 测试步骤3：空套件和用例数据导入（另一个空测试）
$suites3 = array();
$cases3 = array();
$results3 = array();
$suiteNames3 = array();
$caseTitles3 = array();

// 测试步骤4：空套件和用例数据导入（再一个空测试）
$suites4 = array();
$cases4 = array();
$results4 = array();
$suiteNames4 = array();
$caseTitles4 = array();

// 测试步骤5：空套件和用例数据导入（最后一个空测试）
$suites5 = array();
$cases5 = array();
$results5 = array();
$suiteNames5 = array();
$caseTitles5 = array();

// 5. 执行测试步骤
r($testtaskTest->importDataOfUnitResultTest(1, 1, $suites1, $cases1, $results1, $suiteNames1, $caseTitles1, 'unit')) && p() && e('1'); // 步骤1：空数据导入成功
r($testtaskTest->importDataOfUnitResultTest(2, 2, $suites2, $cases2, $results2, $suiteNames2, $caseTitles2, 'unit')) && p() && e('1'); // 步骤2：空数据导入成功
r($testtaskTest->importDataOfUnitResultTest(3, 2, $suites3, $cases3, $results3, $suiteNames3, $caseTitles3, 'unit')) && p() && e('1'); // 步骤3：空数据导入成功
r($testtaskTest->importDataOfUnitResultTest(4, 1, $suites4, $cases4, $results4, $suiteNames4, $caseTitles4, 'unit')) && p() && e('1'); // 步骤4：空数据导入成功
r($testtaskTest->importDataOfUnitResultTest(5, 3, $suites5, $cases5, $results5, $suiteNames5, $caseTitles5, 'func')) && p() && e('1'); // 步骤5：不同自动化类型空数据导入