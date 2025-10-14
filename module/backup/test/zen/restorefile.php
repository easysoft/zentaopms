#!/usr/bin/env php
<?php

/**

title=测试 backupZen::restoreFile();
timeout=0
cid=0

- 步骤1：正常传入有效的备份文件名属性result @success
- 步骤2：传入空字符串参数属性result @success
- 步骤3：传入null参数属性result @fail
- 步骤4：传入不存在的备份文件名属性result @success
- 步骤5：模拟附件还原失败情况属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($backupTest->restoreFileZenTest('test_backup')) && p('result') && e('success'); // 步骤1：正常传入有效的备份文件名
r($backupTest->restoreFileZenTest('')) && p('result') && e('success'); // 步骤2：传入空字符串参数
r($backupTest->restoreFileZenTest(null)) && p('result') && e('fail'); // 步骤3：传入null参数
r($backupTest->restoreFileZenTest('nonexistent_backup')) && p('result') && e('success'); // 步骤4：传入不存在的备份文件名
r($backupTest->restoreFileZenTest('restore_fail_test')) && p('result') && e('fail'); // 步骤5：模拟附件还原失败情况