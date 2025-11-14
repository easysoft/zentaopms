#!/usr/bin/env php
<?php

/**

title=测试 cneModel::deleteBackup();
timeout=0
cid=15610

- 测试步骤1:正常删除备份,实例ID=1,正常备份名称属性code @200
- 测试步骤2:删除备份,实例ID=2,正常备份名称属性code @200
- 测试步骤3:删除不存在的备份,实例ID=3,不存在的备份名称属性code @200
- 测试步骤4:删除备份,无效实例ID=0属性code @404
- 测试步骤5:删除备份,不存在实例ID=999属性code @404
- 测试步骤6:删除备份,实例ID=4,空备份名称属性code @400
- 测试步骤7:删除备份,实例ID=5,特殊字符备份名称属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->deleteBackupTest(1, 'backup-20241207-1')) && p('code') && e('200'); // 测试步骤1:正常删除备份,实例ID=1,正常备份名称
r($cneTest->deleteBackupTest(2, 'backup-20241207-2')) && p('code') && e('200'); // 测试步骤2:删除备份,实例ID=2,正常备份名称
r($cneTest->deleteBackupTest(3, 'nonexistent-backup')) && p('code') && e('200'); // 测试步骤3:删除不存在的备份,实例ID=3,不存在的备份名称
r($cneTest->deleteBackupTest(0, 'backup-test')) && p('code') && e('404'); // 测试步骤4:删除备份,无效实例ID=0
r($cneTest->deleteBackupTest(999, 'backup-test')) && p('code') && e('404'); // 测试步骤5:删除备份,不存在实例ID=999
r($cneTest->deleteBackupTest(4, '')) && p('code') && e('400'); // 测试步骤6:删除备份,实例ID=4,空备份名称
r($cneTest->deleteBackupTest(5, 'backup-special-#@!')) && p('code') && e('200'); // 测试步骤7:删除备份,实例ID=5,特殊字符备份名称