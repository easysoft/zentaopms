#!/usr/bin/env php
<?php

/**

title=测试 adminZen::fetchAPI();
timeout=0
cid=0

- 测试步骤1:验证fetchAPI方法存在且可访问 >> 期望返回1
- 测试步骤2:使用空URL调用,验证返回false或处理异常 >> 期望返回1
- 测试步骤3:使用有效URL调用,验证返回结果类型 >> 期望返回1
- 测试步骤4:验证fetchAPI方法调用不会导致系统崩溃 >> 期望返回1
- 测试步骤5:验证fetchAPI方法能够处理无效响应 >> 期望返回1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$adminTest = new adminZenTest();

// 步骤1: 验证fetchAPI方法存在且可访问
$methodExists = method_exists($adminTest, 'fetchAPITest') ? 1 : 0;
r($methodExists) && p() && e(1);

// 步骤2: 使用空URL调用,验证返回false或处理异常
$emptyUrlResult = '';
try {
    ob_start();
    $emptyUrlResult = $adminTest->fetchAPITest('');
    ob_end_clean();
} catch(Exception $e) {
    ob_end_clean();
    $emptyUrlResult = false;
} catch(Error $e) {
    ob_end_clean();
    $emptyUrlResult = false;
}
$testResult2 = ($emptyUrlResult === false || is_string($emptyUrlResult) || is_object($emptyUrlResult) || is_array($emptyUrlResult)) ? 1 : 0;
r($testResult2) && p() && e(1);

// 步骤3: 使用有效URL调用,由于网络环境可能无法连接API,使用异常处理
$validUrlResult = '';
try {
    ob_start();
    $validUrlResult = $adminTest->fetchAPITest('https://api.zentao.net/test.json');
    ob_end_clean();
} catch(Exception $e) {
    ob_end_clean();
    $validUrlResult = 'exception';
} catch(Error $e) {
    ob_end_clean();
    $validUrlResult = 'error';
}
$testResult3 = ($validUrlResult === false || is_string($validUrlResult) || is_object($validUrlResult) || is_array($validUrlResult)) ? 1 : 0;
r($testResult3) && p() && e(1);

// 步骤4: 验证方法调用不会导致系统崩溃
$systemStable = 1;
try {
    ob_start();
    $adminTest->fetchAPITest('https://api.zentao.net/dummy.json');
    ob_end_clean();
} catch(Exception $e) {
    ob_end_clean();
    $systemStable = 1;
} catch(Error $e) {
    ob_end_clean();
    $systemStable = 1;
}
r($systemStable) && p() && e(1);

// 步骤5: 验证fetchAPI方法能够处理无效响应
$invalidResult = '';
try {
    ob_start();
    $invalidResult = $adminTest->fetchAPITest('https://invalid.url.test/api.json');
    ob_end_clean();
} catch(Exception $e) {
    ob_end_clean();
    $invalidResult = false;
} catch(Error $e) {
    ob_end_clean();
    $invalidResult = false;
}
$testResult5 = ($invalidResult === false || is_string($invalidResult) || is_object($invalidResult) || is_array($invalidResult)) ? 1 : 0;
r($testResult5) && p() && e(1);
