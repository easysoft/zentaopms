#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->processSummary();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$backupPath  = dirname(__FILE__) . DS;
$backupFile  = $backupPath . 'test.file';
$summaryFile = $backupPath . 'summary';
$backupModel->processSummary($backupFile, 3, 12, array(), 10);
r(json_decode(file_get_contents($summaryFile), true)['test.file']) && p('allCount,count,size') && e('10,3,12'); //检查统计信息。

$backupModel->processSummary($backupFile, 3, 12, array(), 10, 'delete');
r(json_decode(file_get_contents($summaryFile), true)) && p('test.file') && e('0'); //设置删除的统计信息。

unlink($summaryFile);
