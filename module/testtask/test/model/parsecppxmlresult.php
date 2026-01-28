#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::parseCppXMLResult();
timeout=0
cid=19210

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：解析包含失败测试的C++XML结果
$failXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<TestResult>
    <FailedTests>
        <FailedTest name="TestMath::testAdd">
            <Message>Expected 5 but was 4</Message>
        </FailedTest>
        <FailedTest name="TestMath::testSubtract">
            <Message>Expected 1 but was 2</Message>
        </FailedTest>
    </FailedTests>
    <SuccessfulTests>
    </SuccessfulTests>
</TestResult>';
$failXmlObject = simplexml_load_string($failXmlContent);
$result1 = $testtaskTest->parseCppXMLResultTest($failXmlObject, 1, 'cppunit');
r(is_array($result1)) && p() && e('1');

// 测试步骤2：解析包含成功测试的C++XML结果
$passXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<TestResult>
    <FailedTests>
    </FailedTests>
    <SuccessfulTests>
        <Test name="TestString::testConcat" />
        <Test name="TestString::testLength" />
    </SuccessfulTests>
</TestResult>';
$passXmlObject = simplexml_load_string($passXmlContent);
$result2 = $testtaskTest->parseCppXMLResultTest($passXmlObject, 1, 'cppunit');
r(is_array($result2)) && p() && e('1');

// 测试步骤3：解析包含混合测试结果的C++XML结果
$mixedXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<TestResult>
    <FailedTests>
        <FailedTest name="TestArray::testSort">
            <Message>Array not sorted correctly</Message>
        </FailedTest>
    </FailedTests>
    <SuccessfulTests>
        <Test name="TestArray::testSize" />
        <Test name="TestArray::testEmpty" />
    </SuccessfulTests>
</TestResult>';
$mixedXmlObject = simplexml_load_string($mixedXmlContent);
$result3 = $testtaskTest->parseCppXMLResultTest($mixedXmlObject, 1, 'cppunit');
r(is_array($result3)) && p() && e('1');

// 测试步骤4：解析空的XML结果
$emptyXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<TestResult>
    <FailedTests>
    </FailedTests>
    <SuccessfulTests>
    </SuccessfulTests>
</TestResult>';
$emptyXmlObject = simplexml_load_string($emptyXmlContent);
$result4 = $testtaskTest->parseCppXMLResultTest($emptyXmlObject, 1, 'cppunit');
r(is_array($result4)) && p() && e('1');

// 测试步骤5：解析无效的XML结构
$invalidXmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<InvalidStructure>
    <SomeElement>Test</SomeElement>
</InvalidStructure>';
$invalidXmlObject = simplexml_load_string($invalidXmlContent);
$result5 = $testtaskTest->parseCppXMLResultTest($invalidXmlObject, 1, 'cppunit');
r(is_array($result5)) && p() && e('1');