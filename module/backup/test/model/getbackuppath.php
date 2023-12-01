#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->getBackupPath();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

r(strpos($backupModel->getBackupPath(), '/tmp/backup/') !== false) && p() && e('1');   //默认备份路径。

$backupModel->config->backup->settingDir = dirname(__FILE__) . DS . 'backup' . DS;
r(strpos($backupModel->getBackupPath(), 'module/backup/test/model/backup/') !== false) && p() && e('1'); //设置备份路径。
