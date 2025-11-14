#!/usr/bin/env php
<?php

/**

title=测试 backupModel::restoreFile();
timeout=0
cid=15141

- 步骤1：正常传入有效的备份目录路径
 - 属性result @1
 - 属性error @~~
- 步骤2：传入空字符串参数
 - 属性result @1
 - 属性error @~~
- 步骤3：传入不存在的路径
 - 属性result @1
 - 属性error @~~
- 步骤4：传入文件路径而非目录
 - 属性result @1
 - 属性error @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 准备测试数据
$testBackupDir = dirname(__FILE__, 5) . '/tmp/test_backup_dir/';
$testFilePath = dirname(__FILE__, 5) . '/tmp/test_backup_file.txt';
$testDataDir = dirname(__FILE__, 5) . '/www/data/';

// 创建测试目录和文件
if(!is_dir($testBackupDir)) mkdir($testBackupDir, 0777, true);
if(!is_dir($testDataDir)) mkdir($testDataDir, 0777, true);
if(!file_exists($testFilePath)) file_put_contents($testFilePath, 'test content');

// 5. 强制要求：必须包含至少5个测试步骤
r($backupTest->restoreFileTest($testBackupDir)) && p('result,error') && e('1,~~'); // 步骤1：正常传入有效的备份目录路径
r($backupTest->restoreFileTest('')) && p('result,error') && e('1,~~'); // 步骤2：传入空字符串参数
r($backupTest->restoreFileTest('/nonexistent/path/')) && p('result,error') && e('1,~~'); // 步骤3：传入不存在的路径
r($backupTest->restoreFileTest($testFilePath)) && p('result,error') && e('1,~~'); // 步骤4：传入文件路径而非目录