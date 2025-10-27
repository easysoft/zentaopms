#!/usr/bin/env php
<?php

/**

title=测试 mrZen::checkNewCommit();
timeout=0
cid=0

- 步骤1：gitlab平台有新提交 @1
- 步骤2：gitlab平台无新提交 @0
- 步骤3：gitea平台有新提交 @1
- 步骤4：gogs平台有新提交 @1
- 步骤5：无效参数输入 @invalid_hosttype

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$mrTest = new mrTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($mrTest->checkNewCommitTest('gitlab', 1, '100', 1, '2023-11-30 08:00:00')) && p() && e('1'); // 步骤1：gitlab平台有新提交
r($mrTest->checkNewCommitTest('gitlab', 1, '100', 1, '2023-12-01 12:00:00')) && p() && e('0'); // 步骤2：gitlab平台无新提交
r($mrTest->checkNewCommitTest('gitea', 1, '100', 2, '2023-12-01 08:00:00')) && p() && e('1'); // 步骤3：gitea平台有新提交
r($mrTest->checkNewCommitTest('gogs', 1, '100', 2, '2023-12-01 08:00:00')) && p() && e('1'); // 步骤4：gogs平台有新提交
r($mrTest->checkNewCommitTest('', 0, '', 0, '')) && p() && e('invalid_hosttype'); // 步骤5：无效参数输入