#!/usr/bin/env php
<?php

/**

title=测试 backupModel::getBackupPath();
timeout=0
cid=15134

- 执行backupTest模块的getBackupPathTest方法，参数是, '/tmp/backup/') !== false  @1
- 执行backupTest模块的getBackupPathTest方法，参数是, 'module/backup/test/model/backup/') !== false  @1
- 执行backupTest模块的getBackupPathTest方法  @/test/backup/
- 执行backupTest模块的getBackupPathTest方法  @C:\test\backup\/
- 执行backupTest模块的getBackupPathTest方法  @/test path/backup with spaces/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$backupTest = new backupModelTest();

global $tester;
$backupModel = $tester->loadModel('backup');

r(strpos($backupTest->getBackupPathTest(), '/tmp/backup/') !== false) && p() && e('1');

$backupModel->config->backup->settingDir = dirname(__FILE__) . DS . 'backup' . DS;
r(strpos($backupTest->getBackupPathTest(), 'module/backup/test/model/backup/') !== false) && p() && e('1');

$backupModel->config->backup->settingDir = '/test/backup//';
r($backupTest->getBackupPathTest()) && p() && e('/test/backup/');

$backupModel->config->backup->settingDir = 'C:\\test\\backup\\';
r($backupTest->getBackupPathTest()) && p() && e('C:\test\backup\/');

$backupModel->config->backup->settingDir = '/test path/backup with spaces/';
r($backupTest->getBackupPathTest()) && p() && e('/test path/backup with spaces/');