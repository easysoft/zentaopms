#!/usr/bin/env php
<?php

/**

title=测试 biModel::saveLogs();
cid=15215

- 测试步骤1：正常日志信息保存 >> 期望成功创建日志文件并包含正确内容
- 测试步骤2：空字符串日志保存 >> 期望正常处理空内容但仍创建文件结构
- 测试步骤3：包含特殊字符的日志保存 >> 期望正确处理特殊字符内容
- 测试步骤4：多行日志信息保存 >> 期望正确处理多行内容
- 测试步骤5：长文本日志信息保存 >> 期望正确处理长文本内容

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($biTest->saveLogsTest('Test log message')) && p('fileExists,hasPhpHeader,hasDieStatement,hasLogContent,hasTimestamp') && e('1,1,1,1,1'); // 步骤1：正常情况
r($biTest->saveLogsTest('')) && p('fileExists,hasPhpHeader,hasDieStatement,hasTimestamp') && e('1,1,1,1'); // 步骤2：空字符串
r($biTest->saveLogsTest('Log with special chars: @#$%^&*()')) && p('fileExists,hasLogContent,hasTimestamp') && e('1,1,1'); // 步骤3：特殊字符
r($biTest->saveLogsTest("Multi line log message with breaks")) && p('fileExists,hasLogContent,hasTimestamp') && e('1,1,1'); // 步骤4：多行文本
r($biTest->saveLogsTest('Long log message content test')) && p('fileExists,hasLogContent,hasTimestamp') && e('1,1,1'); // 步骤5：长文本内容