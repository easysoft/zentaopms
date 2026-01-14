#!/usr/bin/env php
<?php

/**

title=测试 backupModel::backSQL();
timeout=0
cid=15131

- 执行backupTest模块的backSQLTest方法，参数是'/tmp/test_backup' 属性result @1
- 执行backupTest模块的backSQLTest方法，参数是'backup/test.sql' 属性result @~~
- 执行backupTest模块的backSQLTest方法，参数是'/tmp/zentao_backup.sql' 属性result @1
- 执行backupTest模块的backSQLTest方法，参数是'/tmp/very_long_backup_file_name_for_testing_purpose.sql' 属性result @1
- 执行backupTest模块的backSQLTest方法，参数是'/tmp/backup_测试-2024.sql' 属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$backupTest = new backupModelTest();

r($backupTest->backSQLTest('/tmp/test_backup')) && p('result') && e('1');
r($backupTest->backSQLTest('backup/test.sql')) && p('result') && e('~~');
r($backupTest->backSQLTest('/tmp/zentao_backup.sql')) && p('result') && e('1');
r($backupTest->backSQLTest('/tmp/very_long_backup_file_name_for_testing_purpose.sql')) && p('result') && e('1');
r($backupTest->backSQLTest('/tmp/backup_测试-2024.sql')) && p('result') && e('1');