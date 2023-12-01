#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->addFileHeader();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$fileName = dirname(__FILE__) . DS . 'test';
file_put_contents($fileName, 'test');

$backupModel->addFileHeader($fileName);
r(filesize($fileName)) && p() && e('19');

unlink($fileName);
