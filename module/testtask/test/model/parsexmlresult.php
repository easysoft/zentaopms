#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::parseXMLResult();
timeout=0
cid=19211

- 执行$result1['suites']) && isset($result1['cases']) && isset($result1['results'] @1
- 执行$result2['suites']) && count($result2['cases']) > 0 @1
- 执行$result3['suites']) && is_array($result3['cases'] @1
- 执行$result4) && isset($result4['suites'] @1
- 执行$result5) && empty($result5['suites']) && empty($result5['cases'] @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：解析标准JUnit XML格式结果
$junitXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="TestSuite1" tests="3" failures="0" errors="0" skipped="0" time="0.123">
    <testcase classname="com.example.Calculator" name="testAdd" time="0.045"></testcase>
    <testcase classname="com.example.Calculator" name="testSubtract" time="0.038"></testcase>
    <testcase classname="com.example.Math" name="testMultiply" time="0.040"></testcase>
</testsuite>';
$junitXmlObject = simplexml_load_string($junitXmlContent);
$result1 = $testtaskTest->parseXMLResultTest($junitXmlObject, 1, 'junit');
r(isset($result1['suites']) && isset($result1['cases']) && isset($result1['results'])) && p() && e('1');

// 测试步骤2：解析包含失败用例的XML结果
$failureXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="TestSuite2" tests="2" failures="1" errors="0" skipped="0" time="0.089">
    <testcase classname="com.example.Calculator" name="testDivide" time="0.044">
        <failure message="Division by zero error">Expected: 5.0 but was: Infinity</failure>
    </testcase>
    <testcase classname="com.example.Calculator" name="testAdd" time="0.045"></testcase>
</testsuite>';
$failureXmlObject = simplexml_load_string($failureXmlContent);
$result2 = $testtaskTest->parseXMLResultTest($failureXmlObject, 1, 'junit');
r(isset($result2['suites']) && count($result2['cases']) > 0) && p() && e('1');

// 测试步骤3：解析包含跳过用例的XML结果
$skippedXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="TestSuite3" tests="3" failures="0" errors="0" skipped="1" time="0.067">
    <testcase classname="com.example.Calculator" name="testAdd" time="0.033"></testcase>
    <testcase classname="com.example.Calculator" name="testSkipped" time="0.000">
        <skipped message="Test skipped temporarily"></skipped>
    </testcase>
    <testcase classname="com.example.Calculator" name="testSubtract" time="0.034"></testcase>
</testsuite>';
$skippedXmlObject = simplexml_load_string($skippedXmlContent);
$result3 = $testtaskTest->parseXMLResultTest($skippedXmlObject, 1, 'junit');
r(isset($result3['suites']) && is_array($result3['cases'])) && p() && e('1');

// 测试步骤4：解析PHPUnit格式的XML结果
$phpunitXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<testsuite name="PHPUnitSuite" tests="2" failures="0" errors="0" skipped="0" time="0.156">
    <testcase className="UserModelTest" methodName="testGetById" time="0.078"></testcase>
    <testcase className="UserModelTest" methodName="testCreate" time="0.078"></testcase>
</testsuite>';
$phpunitXmlObject = simplexml_load_string($phpunitXmlContent);
$result4 = $testtaskTest->parseXMLResultTest($phpunitXmlObject, 1, 'phpunit');
r(is_array($result4) && isset($result4['suites'])) && p() && e('1');

// 测试步骤5：解析空XML或无效结构
$emptyXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<invalidstructure>
    <randomnode>no test data</randomnode>
</invalidstructure>';
$emptyXmlObject = simplexml_load_string($emptyXmlContent);
$result5 = $testtaskTest->parseXMLResultTest($emptyXmlObject, 1, 'junit');
r(is_array($result5) && empty($result5['suites']) && empty($result5['cases'])) && p() && e('1');