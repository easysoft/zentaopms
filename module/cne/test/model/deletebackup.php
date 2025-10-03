#!/usr/bin/env php
<?php

/**

title=测试 cneModel::deleteBackup();
timeout=0
cid=0

- 执行cneTest模块的deleteBackupTest方法，参数是1, 'backup-20231201-001' 属性code @200
- 执行cneTest模块的deleteBackupTest方法，参数是2, 'nonexistent-backup' 属性code @200
- 执行cneTest模块的deleteBackupTest方法，参数是1, '' 属性code @400
- 执行cneTest模块的deleteBackupTest方法，参数是3, 'backup-special-chars' 属性code @200
- 执行cneTest模块的deleteBackupTest方法，参数是4, 'backup-consistency-test' 属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->deleteBackupTest(1, 'backup-20231201-001')) && p('code') && e('200');
r($cneTest->deleteBackupTest(2, 'nonexistent-backup')) && p('code') && e('200');
r($cneTest->deleteBackupTest(1, '')) && p('code') && e('400');
r($cneTest->deleteBackupTest(3, 'backup-special-chars')) && p('code') && e('200');
r($cneTest->deleteBackupTest(4, 'backup-consistency-test')) && p('code') && e('200');