#!/usr/bin/env php
<?php

/**

title=测试 backupModel::getDirSize();
timeout=0
cid=15136

- 执行backupTest模块的getDirSizeTest方法，参数是'/nonexistent/dir/path'  @0
- 执行backupTest模块的getDirSizeTest方法，参数是''  @0
- 执行backupTest模块的getDirSizeTest方法，参数是null  @0
- 执行backupTest模块的getDirSizeTest方法，参数是dirname  @1
- 执行backupTest模块的getDirSizeTest方法，参数是dirname  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

su('admin');

$backupTest = new backupTest();

r($backupTest->getDirSizeTest('/nonexistent/dir/path')) && p() && e(0);
r($backupTest->getDirSizeTest('')) && p() && e(0);
r($backupTest->getDirSizeTest(null)) && p() && e(0);
r($backupTest->getDirSizeTest(dirname(__FILE__, 5) . '/test') > 0) && p() && e(1);
r($backupTest->getDirSizeTest(dirname(__FILE__, 2)) > 0) && p() && e(1);