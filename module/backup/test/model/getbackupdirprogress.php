#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->getBackupDirProgress();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$backupName  = dirname(__FILE__) . '/test';
r($backupModel->getBackupDirProgress($backupName)) && p() && e('0');

$summaryFile = $backupModel->getTmpLogFile($backupName);
file_put_contents($summaryFile, json_encode(array('size' => '1234')));
r($backupModel->getBackupDirProgress($backupName)) && p('size') && e('1234');

unlink($summaryFile);

