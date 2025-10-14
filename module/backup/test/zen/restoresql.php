#!/usr/bin/env php
<?php

/**

title=测试 backupZen::restoreSQL();
timeout=0
cid=0

- 步骤1：正常文件名属性result @success
- 步骤2：空文件名参数属性result @success
- 步骤3：不存在的备份文件属性result @fail
- 步骤4：损坏的备份文件属性result @fail
- 步骤5：权限不足属性result @fail
- 步骤6：无效格式属性result @fail
- 步骤7：还原失败测试属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($backupTest->restoreSQLZenTest('test_backup')) && p('result') && e('success'); // 步骤1：正常文件名
r($backupTest->restoreSQLZenTest('')) && p('result') && e('success'); // 步骤2：空文件名参数
r($backupTest->restoreSQLZenTest('nonexistent')) && p('result') && e('fail'); // 步骤3：不存在的备份文件
r($backupTest->restoreSQLZenTest('corrupted')) && p('result') && e('fail'); // 步骤4：损坏的备份文件
r($backupTest->restoreSQLZenTest('permission_denied')) && p('result') && e('fail'); // 步骤5：权限不足
r($backupTest->restoreSQLZenTest('invalid_format')) && p('result') && e('fail'); // 步骤6：无效格式
r($backupTest->restoreSQLZenTest('restore_fail_test')) && p('result') && e('fail'); // 步骤7：还原失败测试