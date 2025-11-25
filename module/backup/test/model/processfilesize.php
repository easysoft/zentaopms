#!/usr/bin/env php
<?php

/**

title=测试 backupModel::processFileSize();
timeout=0
cid=15139

- 执行backupTest模块的processFileSizeTest方法  @0KB
- 执行backupTest模块的processFileSizeTest方法，参数是512  @0.5KB
- 执行backupTest模块的processFileSizeTest方法，参数是1024  @1KB
- 执行backupTest模块的processFileSizeTest方法，参数是1024 * 1024  @1MB
- 执行backupTest模块的processFileSizeTest方法，参数是1024 * 1024 * 1024  @1GB
- 执行backupTest模块的processFileSizeTest方法，参数是1024 * 1024 * 1024 * 1024  @1024GB
- 执行backupTest模块的processFileSizeTest方法，参数是1536 * 1024 * 1024  @1.5GB

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

su('admin');

$backupTest = new backupTest();

r($backupTest->processFileSizeTest(0)) && p() && e('0KB');
r($backupTest->processFileSizeTest(512)) && p() && e('0.5KB');
r($backupTest->processFileSizeTest(1024)) && p() && e('1KB');
r($backupTest->processFileSizeTest(1024 * 1024)) && p() && e('1MB');
r($backupTest->processFileSizeTest(1024 * 1024 * 1024)) && p() && e('1GB');
r($backupTest->processFileSizeTest(1024 * 1024 * 1024 * 1024)) && p() && e('1024GB');
r($backupTest->processFileSizeTest(1536 * 1024 * 1024)) && p() && e('1.5GB');