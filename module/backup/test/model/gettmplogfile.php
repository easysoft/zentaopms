#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->getTmpLogFile();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$summaryFile = dirname(__FILE__) . DS . 'test';
r($backupModel->getTmpLogFile($summaryFile) == $summaryFile . '.tmp.summary') && p() && e('1'); //设置备份路径。
