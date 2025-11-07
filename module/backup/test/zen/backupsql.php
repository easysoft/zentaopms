#!/usr/bin/env php
<?php

/**

title=测试 backupZen::backupSQL();
timeout=0
cid=0

- 步骤1:正常备份SQL文件属性result @success
- 步骤2:reload=yes参数测试属性result @success
- 步骤3:reload=no参数测试属性result @success
- 步骤4:nosafe配置测试属性result @success
- 步骤5:日期格式文件名测试属性result @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$backupTest = new backupZenTest();

// 4. 测试步骤
r($backupTest->backupSQLTest('test_sql_' . time())) && p('result') && e('success'); // 步骤1:正常备份SQL文件
r($backupTest->backupSQLTest('test_sql_yes_' . time(), 'yes')) && p('result') && e('success'); // 步骤2:reload=yes参数测试
r($backupTest->backupSQLTest('test_sql_no_' . time(), 'no')) && p('result') && e('success'); // 步骤3:reload=no参数测试
r($backupTest->backupSQLTestWithNosafe()) && p('result') && e('success'); // 步骤4:nosafe配置测试
r($backupTest->backupSQLTest('backup_sql_' . date('YmdHis'))) && p('result') && e('success'); // 步骤5:日期格式文件名测试