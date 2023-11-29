#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->removeFileHeader();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$fileName = dirname(__FILE__) . DS . 'test';
file_put_contents($fileName, "<?php die();?" . ">\ntest");

$backupModel->removeFileHeader($fileName);
r(file_get_contents($fileName)) && p() && e('test');

unlink($fileName);
