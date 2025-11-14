#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->getBackupFile();
timeout=0
cid=15133

- 获取代码备份文件目录。 @1
- 获取附件备份文件目录。 @1
- 获取SQL备份文件。 @1
- 获取SQL备份文件PHP扩展文件。 @1
- 获取不存在的备份文件。 @0

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$backupPath = dirname(__FILE__) . DS;
$backupName = 'test';

$backupModel->config->backup->settingDir = $backupPath;

$sqlFile      = "{$backupPath}{$backupName}.sql";
$sqlPhpFile   = "{$backupPath}{$backupName}.sql.php";
$appendixFile = "{$backupPath}{$backupName}.file";
$codeFile     = "{$backupPath}{$backupName}.code";

touch($sqlFile);
touch($sqlPhpFile);
touch($appendixFile);
touch($codeFile);

r($backupModel->getBackupFile($backupName, 'code') == $codeFile) && p() && e('1');             //获取代码备份文件目录。
unlink($codeFile);

r($backupModel->getBackupFile($backupName, 'file') == $appendixFile) && p() && e('1');         //获取附件备份文件目录。
unlink($appendixFile);

r($backupModel->getBackupFile($backupName, 'sql') == $sqlFile) && p() && e('1');               //获取SQL备份文件。
unlink($sqlFile);

r($backupModel->getBackupFile($backupName, 'sql') == $sqlPhpFile) && p() && e('1');            //获取SQL备份文件PHP扩展文件。
unlink($sqlPhpFile);

r($backupModel->getBackupFile($backupName, 'sql')) && p() && e('0');                           //获取不存在的备份文件。