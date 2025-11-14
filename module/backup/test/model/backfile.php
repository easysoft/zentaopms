#!/usr/bin/env php
<?php

/**

title=测试 backupModel::backFile();
timeout=0
cid=15130

- 步骤1：正常备份目录属性result @1
- 步骤2：备份目录已存在属性result @1
- 步骤3：另一个有效备份路径属性result @1
- 步骤4：zentao命名的备份路径属性result @1
- 步骤5：特殊测试路径属性result @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$backupTest = new backupTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($backupTest->backFileTest('/tmp/test_backup_normal')) && p('result') && e('1'); // 步骤1：正常备份目录
r($backupTest->backFileTest('/tmp/test_backup_exist')) && p('result') && e('1'); // 步骤2：备份目录已存在
r($backupTest->backFileTest('/tmp/test_backup_valid')) && p('result') && e('1'); // 步骤3：另一个有效备份路径
r($backupTest->backFileTest('/tmp/zentao_backup_test')) && p('result') && e('1'); // 步骤4：zentao命名的备份路径
r($backupTest->backFileTest('/tmp/backup_special_test')) && p('result') && e('1'); // 步骤5：特殊测试路径