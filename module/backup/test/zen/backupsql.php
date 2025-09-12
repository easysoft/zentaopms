#!/usr/bin/env php
<?php

/**

title=测试 backupZen::backupSQL();
timeout=0
cid=0

- 步骤1：正常情况属性result @success
- 步骤2：reload为yes属性result @success
- 步骤3：空文件名属性result @success
- 步骤4：特殊字符属性result @success
- 步骤5：中文文件名属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($backupTest->backupSQLTest('test_backup_1', 'no')) && p('result') && e('success'); // 步骤1：正常情况
r($backupTest->backupSQLTest('test_backup_2', 'yes')) && p('result') && e('success'); // 步骤2：reload为yes
r($backupTest->backupSQLTest('', 'no')) && p('result') && e('success'); // 步骤3：空文件名
r($backupTest->backupSQLTest('test-backup_3@special', 'no')) && p('result') && e('success'); // 步骤4：特殊字符
r($backupTest->backupSQLTest('测试备份_4', 'yes')) && p('result') && e('success'); // 步骤5：中文文件名