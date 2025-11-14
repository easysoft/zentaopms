#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->getBackupSummary();
timeout=0
cid=15135

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$backupFile = dirname(__FILE__) . DS . 'testsummaryfile.txt';
file_put_contents($backupFile, 'test');
r($backupModel->getBackupSummary($backupFile)) && p('size') && e('4');   //获取备份文件统计信息。

$backupDir   = dirname(__FILE__) . DS . 'testsummarydir';
r($backupModel->getBackupSummary($backupDir)) && p() && e('0');   //获取没有描述文件的备份目录统计信息。

$summaryFile = dirname(__FILE__) . DS . 'summary';
$summary     = array('testsummarydir' => array('allCount' => 3, 'count' => 2, 'size' => 1024));
file_put_contents($summaryFile, json_encode($summary));
r($backupModel->getBackupSummary($backupDir)) && p('allCount,count,size') && e('3,2,1024');   //获取有描述文件的备份目录统计信息。

unlink($backupFile);
unlink($backupDir);
unlink($summaryFile);
