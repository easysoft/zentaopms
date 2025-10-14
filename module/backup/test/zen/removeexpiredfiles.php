#!/usr/bin/env php
<?php

/**

title=测试 backupZen::removeExpiredFiles();
timeout=0
cid=0

- 步骤1：正常情况，默认14天保留期
 - 属性removed @2
 - 属性kept @2
- 步骤2：所有文件都未过期
 - 属性removed @0
 - 属性kept @3
- 步骤3：所有文件都过期
 - 属性removed @3
 - 属性kept @0
- 步骤4：空目录
 - 属性removed @0
 - 属性kept @0
- 步骤5：包含非备份文件
 - 属性removed @1
 - 属性kept @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$backupTest = new backupTest();

// 4. 执行至少5个测试步骤
r($backupTest->removeExpiredFilesTest(null, 14)) && p('removed,kept') && e('2,2'); // 步骤1：正常情况，默认14天保留期
r($backupTest->removeExpiredFilesTest(array('20240101.sql' => time() - (5 * 24 * 3600), '20240110.file' => time() - (3 * 24 * 3600), '20240115.code' => time() - (1 * 24 * 3600)), 7)) && p('removed,kept') && e('0,3'); // 步骤2：所有文件都未过期
r($backupTest->removeExpiredFilesTest(array('20240101.sql' => time() - (30 * 24 * 3600), '20240110.file' => time() - (25 * 24 * 3600), '20240115.code' => time() - (16 * 24 * 3600)), 14)) && p('removed,kept') && e('3,0'); // 步骤3：所有文件都过期
r($backupTest->removeExpiredFilesTest(array(), 14)) && p('removed,kept') && e('0,0'); // 步骤4：空目录
r($backupTest->removeExpiredFilesTest(array('20240101.sql' => time() - (20 * 24 * 3600), '20240110.file' => time() - (10 * 24 * 3600), 'other.txt' => time() - (20 * 24 * 3600)), 14)) && p('removed,kept') && e('1,1'); // 步骤5：包含非备份文件