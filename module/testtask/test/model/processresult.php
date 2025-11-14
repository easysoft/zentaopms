#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::processResult();
timeout=0
cid=19215

- 步骤1：正常情况无failure或skipped属性caseResult @pass
- 步骤2：处理failure字符串属性caseResult @fail
- 步骤3：处理skipped节点属性caseResult @n/a
- 步骤4：处理failure数组节点属性caseResult @fail
- 步骤5：处理failure message属性属性caseResult @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskTest();

// 4. 准备测试数据和对象
$now = helper::now();

// 创建正常的XML节点（无failure或skipped）
$normalXml = new SimpleXMLElement('<testcase name="test1" time="0.5"></testcase>');

// 创建带failure字符串的XML节点
$failureStringXml = new SimpleXMLElement('<testcase name="test2" time="0.5"><failure>Test failed: assertion error</failure></testcase>');

// 创建带skipped的XML节点
$skippedXml = new SimpleXMLElement('<testcase name="test3" time="0.5"><skipped>Test skipped</skipped></testcase>');

// 创建带failure数组的XML节点
$failureArrayXml = new SimpleXMLElement('<testcase name="test4" time="0.5"><failure>First failure message</failure></testcase>');

// 创建带failure message属性的XML节点
$failureMessageXml = new SimpleXMLElement('<testcase name="test5" time="0.5"><failure message="Assertion failed: expected true but was false"></failure></testcase>');

// 辅助函数：创建基础结果对象
function createBaseResult($now) {
    $result = new stdclass();
    $result->case = 0;
    $result->version = 1;
    $result->caseResult = 'pass';
    $result->lastRunner = 'admin';
    $result->date = $now;
    $result->stepResults[0]['result'] = 'pass';
    $result->stepResults[0]['real'] = '';
    return $result;
}

// 5. 强制要求：必须包含至少5个测试步骤
r($testtaskTest->processResultTest(createBaseResult($now), $normalXml, 'failure', 'skipped')) && p('caseResult') && e('pass'); // 步骤1：正常情况无failure或skipped
r($testtaskTest->processResultTest(createBaseResult($now), $failureStringXml, 'failure', 'skipped')) && p('caseResult') && e('fail'); // 步骤2：处理failure字符串
r($testtaskTest->processResultTest(createBaseResult($now), $skippedXml, 'failure', 'skipped')) && p('caseResult') && e('n/a'); // 步骤3：处理skipped节点
r($testtaskTest->processResultTest(createBaseResult($now), $failureArrayXml, 'failure', 'skipped')) && p('caseResult') && e('fail'); // 步骤4：处理failure数组节点
r($testtaskTest->processResultTest(createBaseResult($now), $failureMessageXml, 'failure', 'skipped')) && p('caseResult') && e('fail'); // 步骤5：处理failure message属性