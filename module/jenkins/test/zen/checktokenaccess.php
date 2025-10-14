#!/usr/bin/env php
<?php

/**

title=测试 jenkinsZen::checkTokenAccess();
timeout=0
cid=0

- 步骤1：无效Jenkins URL（实际会失败） @1
- 步骤2：使用token认证（实际会失败） @1
- 步骤3：无效Jenkins URL @1
- 步骤4：空URL参数 @1
- 步骤5：空账号和密码 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/jenkins.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$jenkinsTest = new jenkinsTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($jenkinsTest->checkTokenAccessTest('http://valid.jenkins.url', 'validuser', 'validpass', '')) && p() && e(1); // 步骤1：无效Jenkins URL（实际会失败）
r($jenkinsTest->checkTokenAccessTest('http://jenkins.example.com', 'user', '', 'validtoken123')) && p() && e(1); // 步骤2：使用token认证（实际会失败）
r($jenkinsTest->checkTokenAccessTest('http://invalid.url', 'user', 'pass', '')) && p() && e(1); // 步骤3：无效Jenkins URL
r($jenkinsTest->checkTokenAccessTest('', 'user', 'pass', '')) && p() && e(1); // 步骤4：空URL参数
r($jenkinsTest->checkTokenAccessTest('http://jenkins.example.com', '', '', '')) && p() && e(1); // 步骤5：空账号和密码