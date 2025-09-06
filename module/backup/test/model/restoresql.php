#!/usr/bin/env php
<?php

/**

title=测试 backupModel::restoreSQL();
timeout=0
cid=0

- 执行backupTest模块的restoreSQLTest方法，参数是'/tmp/test_backup.sql' 属性result @1
- 执行backupTest模块的restoreSQLTest方法，参数是'/nonexistent/path/backup.sql' 属性result @0
- 执行backupTest模块的restoreSQLTest方法，参数是'' 属性result @0
- 执行backupTest模块的restoreSQLTest方法，参数是'/tmp/invalid.txt' 属性result @0
- 执行backupTest模块的restoreSQLTest方法，参数是'/tmp/backup_测试-2024.sql' 属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

su('admin');

$backupTest = new backupTest();

r($backupTest->restoreSQLTest('/tmp/test_backup.sql')) && p('result') && e('1');
r($backupTest->restoreSQLTest('/nonexistent/path/backup.sql')) && p('result') && e('0');
r($backupTest->restoreSQLTest('')) && p('result') && e('0');
r($backupTest->restoreSQLTest('/tmp/invalid.txt')) && p('result') && e('0');
r($backupTest->restoreSQLTest('/tmp/backup_测试-2024.sql')) && p('result') && e('1');