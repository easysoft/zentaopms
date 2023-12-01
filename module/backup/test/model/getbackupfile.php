#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=backupModel->getBackupFile();
timeout=0
cid=1

*/

global $tester;
$backupModel = $tester->loadModel('backup');

$backupPath = dirname(__FILE__) . DS;
$backupName = 'test';

$backupModel->config->backup->settingDir = $backupPath;

$sqlFile    = "{$backupPath}{$backupName}.sql";
$sqlPhpFile = "{$backupPath}{$backupName}.sql.php";

$appendixFile       = "{$backupPath}{$backupName}.file";
$zipAppendixFile    = "{$backupPath}{$backupName}.file.zip";
$zipPhpAppendixFile = "{$backupPath}{$backupName}.file.zip.php";

$codeFile       = "{$backupPath}{$backupName}.code";
$zipCodeFile    = "{$backupPath}{$backupName}.code.zip";
$zipPhpCodeFile = "{$backupPath}{$backupName}.code.zip.php";

touch($sqlFile);
touch($sqlPhpFile);
touch($appendixFile);
touch($zipAppendixFile);
touch($zipPhpAppendixFile);
touch($codeFile);
touch($zipCodeFile);
touch($zipPhpCodeFile);

r($backupModel->getBackupFile($backupName, 'code') == $codeFile) && p() && e('1');             //获取代码备份文件目录。
unlink($codeFile);

r($backupModel->getBackupFile($backupName, 'code') == $zipCodeFile) && p() && e('1');          //获取代码备份文件压缩包。
unlink($zipCodeFile);

r($backupModel->getBackupFile($backupName, 'code') == $zipPhpCodeFile) && p() && e('1');       //获取代码备份文件压缩PHP扩展文件。
unlink($zipPhpCodeFile);

r($backupModel->getBackupFile($backupName, 'file') == $appendixFile) && p() && e('1');         //获取附件备份文件目录。
unlink($appendixFile);

r($backupModel->getBackupFile($backupName, 'file') == $zipAppendixFile) && p() && e('1');      //获取附件备份文件压缩包。
unlink($zipAppendixFile);

r($backupModel->getBackupFile($backupName, 'file') == $zipPhpAppendixFile) && p() && e('1');   //获取附件备份文件压缩PHP扩展文件。
unlink($zipPhpAppendixFile);

r($backupModel->getBackupFile($backupName, 'sql') == $sqlFile) && p() && e('1');               //获取SQL备份文件。
unlink($sqlFile);

r($backupModel->getBackupFile($backupName, 'sql') == $sqlPhpFile) && p() && e('1');            //获取SQL备份文件PHP扩展文件。
unlink($sqlPhpFile);

r($backupModel->getBackupFile($backupName, 'sql')) && p() && e('0');                           //获取不存在的备份文件。
