#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->processFileSize();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

r($backupModel->processFileSize(512))                && p() && e('0.5KB');
r($backupModel->processFileSize(1024))               && p() && e('1KB');
r($backupModel->processFileSize(1024 * 1024))        && p() && e('1MB');
r($backupModel->processFileSize(1024 * 1024 * 1024)) && p() && e('1GB');
