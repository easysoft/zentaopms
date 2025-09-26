#!/usr/bin/env php
<?php

/**

title=测试 cneModel::deleteBackup();
timeout=0
cid=0

- 步骤1：正常删除已存在的备份属性code @200
- 步骤2：删除不存在的备份属性code @200
- 步骤3：使用空备份名称删除属性code @400
- 步骤4：删除包含特殊字符的备份名称属性code @200
- 步骤5：验证删除操作的一致性属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 创建测试实例
$cneTest = new cneTest();

// 测试步骤：使用测试类中的deleteBackupTest方法
r($cneTest->deleteBackupTest(1, 'backup-20231201-001')) && p('code') && e('200');       // 步骤1：正常删除已存在的备份
r($cneTest->deleteBackupTest(2, 'nonexistent-backup')) && p('code') && e('200');        // 步骤2：删除不存在的备份
r($cneTest->deleteBackupTest(1, '')) && p('code') && e('400');                          // 步骤3：使用空备份名称删除
r($cneTest->deleteBackupTest(3, 'backup-#special@chars!')) && p('code') && e('200');   // 步骤4：删除包含特殊字符的备份名称
r($cneTest->deleteBackupTest(4, 'backup-consistency-test')) && p('code') && e('200');  // 步骤5：验证删除操作的一致性