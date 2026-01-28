#!/usr/bin/env php
<?php

/**

title=测试 backupModel::getTmpLogFile();
timeout=0
cid=15138

- 执行backupTest模块的getTmpLogFileTest方法，参数是'/tmp/backup_test'  @/tmp/backup_test.tmp.summary
- 执行backupTest模块的getTmpLogFileTest方法，参数是''  @.tmp.summary
- 执行backupTest模块的getTmpLogFileTest方法，参数是'/backup/test with spaces'  @/backup/test with spaces.tmp.summary
- 执行backupTest模块的getTmpLogFileTest方法，参数是'/home/user/backup'  @/home/user/backup.tmp.summary
- 执行backupTest模块的getTmpLogFileTest方法，参数是'./relative/path'  @./relative/path.tmp.summary

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$backupTest = new backupModelTest();

r($backupTest->getTmpLogFileTest('/tmp/backup_test')) && p() && e('/tmp/backup_test.tmp.summary');
r($backupTest->getTmpLogFileTest('')) && p() && e('.tmp.summary');
r($backupTest->getTmpLogFileTest('/backup/test with spaces')) && p() && e('/backup/test with spaces.tmp.summary');
r($backupTest->getTmpLogFileTest('/home/user/backup')) && p() && e('/home/user/backup.tmp.summary');
r($backupTest->getTmpLogFileTest('./relative/path')) && p() && e('./relative/path.tmp.summary');