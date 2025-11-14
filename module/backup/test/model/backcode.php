#!/usr/bin/env php
<?php

/**

title=测试 backupModel::backCode();
timeout=0
cid=15129

- 步骤1：正常备份到新目录属性result @1
- 步骤2：备份到另一个新目录属性result @1
- 步骤3：深层目录自动创建属性result @1
- 步骤4：唯一路径备份属性result @1
- 步骤5：日期命名备份属性result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 准备测试目录
$tmpDir = '/tmp/backup_test_' . time();
$validPath = $tmpDir . '/valid_backup';
$existingPath = $tmpDir . '/existing_backup';
$deepPath = $tmpDir . '/deep/nested/path';
$normalPath = $tmpDir . '/normal_backup';

// 创建测试目录
if(!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);

// 5. 强制要求：必须包含至少5个测试步骤
r($backupTest->backCodeTest($validPath)) && p('result') && e(1); // 步骤1：正常备份到新目录
r($backupTest->backCodeTest($normalPath)) && p('result') && e(1); // 步骤2：备份到另一个新目录
r($backupTest->backCodeTest($deepPath)) && p('result') && e(1); // 步骤3：深层目录自动创建
r($backupTest->backCodeTest($tmpDir . '/test_' . uniqid())) && p('result') && e(1); // 步骤4：唯一路径备份
r($backupTest->backCodeTest($tmpDir . '/final_' . date('Y-m-d'))) && p('result') && e(1); // 步骤5：日期命名备份

// 清理测试目录
system("rm -rf $tmpDir");