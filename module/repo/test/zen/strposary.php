#!/usr/bin/env php
<?php

/**

title=测试 repoZen::strposAry();
timeout=0
cid=18155

- 步骤1：正常匹配测试 @1
- 步骤2：不匹配测试 @0
- 步骤3：空数组测试 @0
- 步骤4：包含空字符串测试 @1
- 步骤5：中文字符匹配测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoZenTest = new repoZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($repoZenTest->strposAryTest('这是一个测试日志fatal错误', array('fatal', 'error', 'failed'))) && p() && e('1'); // 步骤1：正常匹配测试
r($repoZenTest->strposAryTest('这是一个正常的日志信息', array('fatal', 'error', 'failed'))) && p() && e('0'); // 步骤2：不匹配测试
r($repoZenTest->strposAryTest('任何字符串', array())) && p() && e('0'); // 步骤3：空数组测试
r($repoZenTest->strposAryTest('测试字符串', array('', '不存在的'))) && p() && e('1'); // 步骤4：包含空字符串测试
r($repoZenTest->strposAryTest('包含中文字符串的测试内容', array('中文', 'english'))) && p() && e('1'); // 步骤5：中文字符匹配测试