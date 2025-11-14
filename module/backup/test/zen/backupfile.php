#!/usr/bin/env php
<?php

/**

title=测试 backupZen::backupFile();
timeout=0
cid=15144

- 步骤1：配置包含nofile属性result @success
- 步骤2：正常备份属性result @success
- 步骤3：reload=yes属性result @success
- 步骤4：reload=no属性result @success
- 步骤5：日期文件名属性result @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$backupTest = new backupZenTest();

// 4. 测试步骤
r($backupTest->backupFileTestWithNofile()) && p('result') && e('success'); // 步骤1：配置包含nofile
r($backupTest->backupFileTest('test_backup_' . time())) && p('result') && e('success'); // 步骤2：正常备份
r($backupTest->backupFileTest('test_backup_yes_' . time(), 'yes')) && p('result') && e('success'); // 步骤3：reload=yes
r($backupTest->backupFileTest('test_backup_no_' . time(), 'no')) && p('result') && e('success'); // 步骤4：reload=no
r($backupTest->backupFileTest('backup_' . date('YmdHis'))) && p('result') && e('success'); // 步骤5：日期文件名