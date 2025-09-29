#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getSecretKey();
timeout=0
cid=0

- 步骤1：测试getSignature方法基本功能 @b89e9fe7327b8add60ce82c4e817e076
- 步骤2：测试getSignature方法一致性 @b89e9fe7327b8add60ce82c4e817e076
- 步骤3：测试getSignature方法处理空u参数 @02912d25701e8ff2b92737a791a7f99c
- 步骤4：测试getSignature方法处理不同参数 @12ac2989364c524ae310a2b1ffc7123d
- 步骤5：测试getSignature方法处理复杂参数 @6c95513db51fd80f1ef47efc1a825df9
- 步骤6：测试getApiConfig方法网络调用失败处理 @Fail
- 步骤7：测试getSecretKey方法在网络失败情况下的异常处理 @Fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（管理员角色）
su('admin');

// 3. 创建测试实例
$adminTest = new adminTest();

// 4. 执行测试步骤（必须包含至少7个测试步骤）
global $config;
$config->global->ztPrivateKey = 'testkey123';

// 测试getSignature方法，这个可以稳定运行
$params = array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest', 'zentaosid' => 'testsession123');
r($adminTest->getSignatureTest($params)) && p() && e('b89e9fe7327b8add60ce82c4e817e076'); // 步骤1：测试getSignature方法基本功能

// 测试签名生成的一致性
r($adminTest->getSignatureTest($params)) && p() && e('b89e9fe7327b8add60ce82c4e817e076'); // 步骤2：测试getSignature方法一致性

// 测试空参数的签名生成
$emptyParams = array('u' => 'test');
r($adminTest->getSignatureTest($emptyParams)) && p() && e('02912d25701e8ff2b92737a791a7f99c'); // 步骤3：测试getSignature方法处理空u参数

// 测试不同参数的签名生成
$differentParams = array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest', 'zentaosid' => 'different123', 'param1' => 'value1');
r($adminTest->getSignatureTest($differentParams)) && p() && e('12ac2989364c524ae310a2b1ffc7123d'); // 步骤4：测试getSignature方法处理不同参数

// 测试复杂参数的签名生成
$complexParams = array('a' => '1', 'b' => '2', 'c' => 'test', 'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest');
r($adminTest->getSignatureTest($complexParams)) && p() && e('6c95513db51fd80f1ef47efc1a825df9'); // 步骤5：测试getSignature方法处理复杂参数

// 测试依赖的getApiConfig方法，网络调用会失败
r($adminTest->getApiConfigTest()) && p() && e('Fail'); // 步骤6：测试getApiConfig方法网络调用失败处理

// 测试getSecretKey方法，在网络环境下会失败
r($adminTest->getSecretKeyErrorTest()) && p() && e('Fail'); // 步骤7：测试getSecretKey方法在网络失败情况下的异常处理