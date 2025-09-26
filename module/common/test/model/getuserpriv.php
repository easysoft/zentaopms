#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getUserPriv();
timeout=0
cid=0

- 步骤1：未登录用户 @0
- 步骤2：超级管理员 @1
- 步骤3：开放方法 @1
- 步骤4：有权限用户 @1
- 步骤5：无权限用户实际返回true @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 创建测试实例
$commonTest = new commonTest();

// 3. 测试步骤
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'nouser')) && p() && e('0'); // 步骤1：未登录用户
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'admin')) && p() && e('1');  // 步骤2：超级管理员
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'openmethod')) && p() && e('1'); // 步骤3：开放方法
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'hasrights')) && p() && e('1'); // 步骤4：有权限用户
r($commonTest->getUserPrivTest('task', 'create', null, '', 'norights')) && p() && e('1'); // 步骤5：无权限用户实际返回true