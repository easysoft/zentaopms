#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::parseZTFUnitResult();
timeout=0
cid=19213

- 执行$result1) && isset($result1['suites']) && isset($result1['cases']) && isset($result1['results'] @1
- 执行$result2) && count($result2['cases']) > 0 && isset($result2['suites'][1] @1
- 执行$result3) && isset($result3['results']) && count($result3['results']) > 0 @1
- 执行$result4) && empty($result4['suites']) && empty($result4['cases']) && empty($result4['results'] @1
- 执行$result5) && isset($result5['cases'][0]) && count($result5['cases'][0]) >= 2 @1
- 执行$result6) && count($result6['suites']) >= 1 && isset($result6['suites'][1] @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备
$table = zenData('testtask');
$table->id->range('1-10');
$table->name->range('测试单1,测试单2,测试单3');
$table->product->range('1-3');
$table->gen(3);

$caseTable = zenData('case');
$caseTable->id->range('1-20');
$caseTable->title->range('单元测试用例1,单元测试用例2,功能测试用例1,功能测试用例2,集成测试用例1');
$caseTable->product->range('1-3');
$caseTable->auto->range('unit{10},no{10}');
$caseTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskTest();

// 5. 测试步骤

// 步骤1：正常单个测试用例
$normalCase = array(
    (object)array(
        'title' => '正常单元测试用例',
        'duration' => 0.5
    )
);
$result1 = $testtaskTest->parseZTFUnitResultTest($normalCase, 'phpunit', 1, 1, 1);
r(is_array($result1) && isset($result1['suites']) && isset($result1['cases']) && isset($result1['results'])) && p() && e('1');

// 步骤2：包含测试套件的多个测试用例
$suiteCases = array(
    (object)array(
        'testSuite' => 'UserTestSuite',
        'title' => '用户测试用例1',
        'duration' => 1.0
    ),
    (object)array(
        'testSuite' => 'UserTestSuite',
        'title' => '用户测试用例2',
        'duration' => 0.8
    )
);
$result2 = $testtaskTest->parseZTFUnitResultTest($suiteCases, 'phpunit', 1, 1, 1);
r(is_array($result2) && count($result2['cases']) > 0 && isset($result2['suites'][1])) && p() && e('1');

// 步骤3：包含失败测试用例
$failureCase = array(
    (object)array(
        'title' => '失败的测试用例',
        'duration' => 0.3,
        'failure' => (object)array(
            'desc' => 'Expected true but got false'
        )
    )
);
$result3 = $testtaskTest->parseZTFUnitResultTest($failureCase, 'phpunit', 1, 1, 1);
r(is_array($result3) && isset($result3['results']) && count($result3['results']) > 0) && p() && e('1');

// 步骤4：空测试结果数组
$emptyCase = array();
$result4 = $testtaskTest->parseZTFUnitResultTest($emptyCase, 'phpunit', 1, 1, 1);
r(is_array($result4) && empty($result4['suites']) && empty($result4['cases']) && empty($result4['results'])) && p() && e('1');

// 步骤5：无测试套件的多个测试用例
$noSuiteCases = array(
    (object)array(
        'title' => '无套件测试用例1',
        'duration' => 0.4
    ),
    (object)array(
        'title' => '无套件测试用例2',
        'duration' => 0.6
    )
);
$result5 = $testtaskTest->parseZTFUnitResultTest($noSuiteCases, 'phpunit', 1, 1, 1);
r(is_array($result5) && isset($result5['cases'][0]) && count($result5['cases'][0]) >= 2) && p() && e('1');

// 步骤6：复杂混合场景
$mixedCases = array(
    (object)array(
        'testSuite' => 'MixedSuite',
        'title' => '混合测试用例1',
        'duration' => 1.2
    ),
    (object)array(
        'title' => '无套件混合用例',
        'duration' => 0.7,
        'failure' => (object)array(
            'desc' => 'Assertion failed'
        )
    ),
    (object)array(
        'testSuite' => 'MixedSuite',
        'title' => '混合测试用例2',
        'duration' => 0.9
    )
);
$result6 = $testtaskTest->parseZTFUnitResultTest($mixedCases, 'phpunit', 1, 1, 1);
r(is_array($result6) && count($result6['suites']) >= 1 && isset($result6['suites'][1])) && p() && e('1');