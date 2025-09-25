#!/usr/bin/env php
<?php

/**

title=测试 biModel::downloadFile();
timeout=0
cid=0

- 步骤1：空参数测试 @0
- 步骤2：无效URL测试 @0
- 步骤3：不可达URL测试 @0
- 步骤4：不存在目录测试 @0
- 步骤5：404错误测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->downloadFileTest('', '', '')) && p() && e('0'); // 步骤1：空参数测试
r($biTest->downloadFileTest('invalid-url', '/tmp/claude/', 'test.file')) && p() && e('0'); // 步骤2：无效URL测试
r($biTest->downloadFileTest('http://invalid-domain.test/file.txt', '/tmp/claude/', 'test.txt')) && p() && e('0'); // 步骤3：不可达URL测试
r($biTest->downloadFileTest('http://httpbin.org/json', '/nonexistent/', 'test.json')) && p() && e('0'); // 步骤4：不存在目录测试
r($biTest->downloadFileTest('https://httpbin.org/status/404', '/tmp/claude/', 'nonexistent.file')) && p() && e('0'); // 步骤5：404错误测试