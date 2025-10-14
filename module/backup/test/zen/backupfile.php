#!/usr/bin/env php
<?php

/**

title=测试 backupZen::backupFile();
timeout=0
cid=0

- 执行backupTest模块的backupFileTest方法，参数是'test_backup_file' 属性result @success
- 执行backupTest模块的backupFileTest方法，参数是'backup_with_reload', 'yes' 属性result @success
- 执行backupTest模块的backupFileTest方法，参数是'' 属性result @success
- 执行backupTest模块的backupFileTest方法，参数是'fail_test', 'no' 属性result @fail
- 执行backupTest模块的backupFileTest方法，参数是'fail_test', 'yes' 属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

su('admin');

$backupTest = new backupTest();

r($backupTest->backupFileTest('test_backup_file')) && p('result') && e('success');
r($backupTest->backupFileTest('backup_with_reload', 'yes')) && p('result') && e('success');
r($backupTest->backupFileTest('')) && p('result') && e('success');
r($backupTest->backupFileTest('fail_test', 'no')) && p('result') && e('fail');
r($backupTest->backupFileTest('fail_test', 'yes')) && p('result') && e('fail');