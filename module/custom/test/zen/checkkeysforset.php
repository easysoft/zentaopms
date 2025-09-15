#!/usr/bin/env php
<?php

/**

title=测试 customZen::checkKeysForSet();
timeout=0
cid=0

- 执行customTest模块的checkKeysForSetTest方法，参数是array  @success
- 执行customTest模块的checkKeysForSetTest方法，参数是array  @duplicate_error
- 执行customTest模块的checkKeysForSetTest方法，参数是array  @invalid_format
- 执行customTest模块的checkKeysForSetTest方法，参数是array  @number_range_error
- 执行customTest模块的checkKeysForSetTest方法，参数是array  @length_error_10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$customTest = new customTest();

// 4. 测试步骤

// 测试步骤1：正常输入情况（有效key值）
r($customTest->checkKeysForSetTest(array('1', '2', '3'), 'story', 'priList')) && p() && e('success');

// 测试步骤2：重复key值输入
r($customTest->checkKeysForSetTest(array('1', '2', '1'), 'story', 'priList')) && p() && e('duplicate_error');

// 测试步骤3：无效key值输入（特殊字符）
r($customTest->checkKeysForSetTest(array('key@#', 'valid_key', 'key-1'), 'story', 'sourceList')) && p() && e('invalid_format');

// 测试步骤4：数值类型key超出范围
r($customTest->checkKeysForSetTest(array('300', '2', '3'), 'story', 'priList')) && p() && e('number_range_error');

// 测试步骤5：字符串长度超出限制
r($customTest->checkKeysForSetTest(array('verylongkey', 'key2'), 'user', 'roleList')) && p() && e('length_error_10');