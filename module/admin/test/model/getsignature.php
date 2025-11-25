#!/usr/bin/env php
<?php

/**

title=测试 adminModel::getSignature();
timeout=0
cid=14982

- 步骤1：正常参数包含u键的情况 @cd7f3c22953ae195924d7ffaeaa4e2c7
- 步骤2：参数不包含u键的情况 @cd7f3c22953ae195924d7ffaeaa4e2c7
- 步骤3：空参数数组的情况 @e1ed001daa5cba8a0b88990ba4b15065
- 步骤4：复杂参数的情况 @1b7b8833885da44ca317544c5a0da92b
- 步骤5：多个参数的排序情况 @a69ca34d5df3fba555ab155853d2784f
- 步骤6：私钥为空字符串的情况 @b3a23c37bd811a4feab30f92a13b5530
- 步骤7：参数包含特殊字符的情况 @b3887a6f8af889948f22282d398abd4e
- 步骤8：参数包含空值的情况 @595ebadfec9e2251a278bea0e54590a8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

// 2. 用户登录（管理员角色）
su('admin');

// 3. 创建测试实例
$adminTest = new adminTest();

// 4. 设置测试用的私钥
global $config;
$config->global->ztPrivateKey = 'test_private_key_123';

// 5. 执行测试步骤（必须包含至少5个测试步骤）
r($adminTest->getSignatureTest(array('u' => 'community', 'sessionVar' => 'sid123', 'param1' => 'value1'))) && p() && e('cd7f3c22953ae195924d7ffaeaa4e2c7'); // 步骤1：正常参数包含u键的情况
r($adminTest->getSignatureTest(array('sessionVar' => 'sid123', 'param1' => 'value1'))) && p() && e('cd7f3c22953ae195924d7ffaeaa4e2c7'); // 步骤2：参数不包含u键的情况
r($adminTest->getSignatureTest(array())) && p() && e('e1ed001daa5cba8a0b88990ba4b15065'); // 步骤3：空参数数组的情况
r($adminTest->getSignatureTest(array('u' => 'test', 'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest', 'sessionVar' => 'zentaosid', 'method' => 'POST'))) && p() && e('1b7b8833885da44ca317544c5a0da92b'); // 步骤4：复杂参数的情况
r($adminTest->getSignatureTest(array('u' => 'zentao', 'b' => 'second', 'a' => 'first', 'c' => 'third'))) && p() && e('a69ca34d5df3fba555ab155853d2784f'); // 步骤5：多个参数的排序情况

// 6. 测试边界情况和错误处理
$config->global->ztPrivateKey = ''; // 设置空私钥
r($adminTest->getSignatureTest(array('sessionVar' => 'sid123', 'param1' => 'value1'))) && p() && e('b3a23c37bd811a4feab30f92a13b5530'); // 步骤6：私钥为空字符串的情况

$config->global->ztPrivateKey = 'test_private_key_123'; // 恢复测试私钥
r($adminTest->getSignatureTest(array('special' => 'test@#$%^&*()'))) && p() && e('b3887a6f8af889948f22282d398abd4e'); // 步骤7：参数包含特殊字符的情况
r($adminTest->getSignatureTest(array('empty' => '', 'normal' => 'value'))) && p() && e('595ebadfec9e2251a278bea0e54590a8'); // 步骤8：参数包含空值的情况