#!/usr/bin/env php
<?php

/**

title=测试 mailModel::isError();
timeout=0
cid=17013

- 步骤1：初始状态无错误信息 @0
- 步骤2：添加单个错误信息 @1
- 步骤3：添加多个错误信息 @1
- 步骤4：清空错误信息后检查 @0
- 步骤5：添加空字符串错误信息 @1
- 步骤6：重置errors数组为空 @0
- 步骤7：添加数字错误信息 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$mailTest = new mailModelTest();

// 4. 执行测试步骤
r($mailTest->isErrorTest()) && p() && e('0'); // 步骤1：初始状态无错误信息
r($mailTest->objectModel->errors[] = 'SMTP connection failed') && r($mailTest->isErrorTest()) && p() && e('1'); // 步骤2：添加单个错误信息
r($mailTest->objectModel->errors[] = 'Authentication failed') && r($mailTest->isErrorTest()) && p() && e('1'); // 步骤3：添加多个错误信息
r($mailTest->objectModel->getError()) && r($mailTest->isErrorTest()) && p() && e('0'); // 步骤4：清空错误信息后检查
r($mailTest->objectModel->errors[] = '') && r($mailTest->isErrorTest()) && p() && e('1'); // 步骤5：添加空字符串错误信息
r($mailTest->objectModel->errors = array()) && r($mailTest->isErrorTest()) && p() && e('0'); // 步骤6：重置errors数组为空
r($mailTest->objectModel->errors[] = 500) && r($mailTest->isErrorTest()) && p() && e('1'); // 步骤7：添加数字错误信息