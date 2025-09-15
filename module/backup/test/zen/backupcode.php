#!/usr/bin/env php
<?php

/**

title=测试 backupZen::backupCode();
timeout=0
cid=0

- 步骤1：正常文件名备份属性result @success
- 步骤2：正常情况处理属性result @success
- 步骤3：空文件名默认处理属性result @success
- 步骤4：reload为yes的失败情况属性result @fail
- 步骤5：reload为no的失败情况属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($backupTest->backupCodeZenTest('normal_test', 'no')) && p('result') && e('success'); // 步骤1：正常文件名备份
r($backupTest->backupCodeZenTest('nofile_test', 'no')) && p('result') && e('success'); // 步骤2：正常情况处理
r($backupTest->backupCodeZenTest('', 'no')) && p('result') && e('success'); // 步骤3：空文件名默认处理
r($backupTest->backupCodeZenTest('fail_test', 'yes')) && p('result') && e('fail'); // 步骤4：reload为yes的失败情况
r($backupTest->backupCodeZenTest('fail_test', 'no')) && p('result') && e('fail'); // 步骤5：reload为no的失败情况