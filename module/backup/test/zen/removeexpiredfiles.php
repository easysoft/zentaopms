#!/usr/bin/env php
<?php

/**

title=测试 backupZen::removeExpiredFiles();
timeout=0
cid=15147

- 步骤1:空目录不应有错误 @0
- 步骤2:未过期文件保留 @0
- 步骤3:验证文件仍存在 @1
- 步骤4:删除过期SQL文件 @0
- 步骤5:验证过期文件已删除 @0
- 步骤6:删除过期附件文件 @0
- 步骤7:验证过期文件已删除 @0
- 步骤8:删除过期代码文件 @0
- 步骤9:验证过期文件已删除 @0
- 步骤10:执行清理 @0
- 步骤11:非备份文件应保留 @1
- 步骤12:执行混合清理 @0
- 步骤13:未过期文件保留 @1
- 步骤14:过期文件已删除 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester;
$backup = $tester->loadModel('backup');

$backupTest = new backupZenTest();

/* 获取备份路径 */
$backupPath = $backup->getBackupPath();

/* 确保备份目录存在且为空 */
if(!is_dir($backupPath)) mkdir($backupPath, 0777, true);

/* 递归删除目录函数 */
function removeDir($dir)
{
    if(!is_dir($dir)) return;
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach($files as $file)
    {
        $path = $dir . '/' . $file;
        is_dir($path) ? removeDir($path) : unlink($path);
    }
    rmdir($dir);
}

foreach(glob("{$backupPath}*") as $file)
{
    if(is_file($file)) unlink($file);
    if(is_dir($file)) removeDir($file);
}

/* 测试1: 空备份目录 */
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤1:空目录不应有错误

/* 测试2: 未过期的SQL备份文件 */
$recentFile = $backupPath . time() . '.sql';
touch($recentFile);
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤2:未过期文件保留
r(file_exists($recentFile)) && p() && e('1'); // 步骤3:验证文件仍存在
if(file_exists($recentFile)) unlink($recentFile);

/* 测试3: 过期的SQL备份文件 */
global $config;
$holdDays = $config->backup->holdDays ?? 14;
$expiredTime = time() - ($holdDays + 1) * 86400;
$expiredSqlFile = $backupPath . '202301010000' . '.sql';
touch($expiredSqlFile, $expiredTime);
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤4:删除过期SQL文件
r(file_exists($expiredSqlFile)) && p() && e('0'); // 步骤5:验证过期文件已删除

/* 测试4: 过期的附件备份文件 */
$expiredFileBackup = $backupPath . '202301020000' . '.file';
touch($expiredFileBackup, $expiredTime);
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤6:删除过期附件文件
r(file_exists($expiredFileBackup)) && p() && e('0'); // 步骤7:验证过期文件已删除

/* 测试5: 过期的代码备份文件 */
$expiredCodeBackup = $backupPath . '202301030000' . '.code';
touch($expiredCodeBackup, $expiredTime);
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤8:删除过期代码文件
r(file_exists($expiredCodeBackup)) && p() && e('0'); // 步骤9:验证过期文件已删除

/* 测试6: 非备份文件不被删除 */
$normalFile = $backupPath . 'readme.txt';
touch($normalFile, $expiredTime);
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤10:执行清理
r(file_exists($normalFile)) && p() && e('1'); // 步骤11:非备份文件应保留
if(file_exists($normalFile)) unlink($normalFile);

/* 测试7: 混合场景 - 过期和未过期文件 */
$recentFile1 = $backupPath . time() . '.sql';
$recentFile2 = $backupPath . (time() - 100) . '.file';
$expiredFile1 = $backupPath . '202302010000' . '.sql';
$expiredFile2 = $backupPath . '202302020000' . '.code';
touch($recentFile1);
touch($recentFile2);
touch($expiredFile1, $expiredTime);
touch($expiredFile2, $expiredTime);
r($backupTest->removeExpiredFilesTest()) && p() && e('0'); // 步骤12:执行混合清理
r(file_exists($recentFile1) && file_exists($recentFile2)) && p() && e('1'); // 步骤13:未过期文件保留
r(file_exists($expiredFile1) || file_exists($expiredFile2)) && p() && e('0'); // 步骤14:过期文件已删除

/* 清理测试文件 */
foreach(glob("{$backupPath}*") as $file)
{
    if(is_file($file)) unlink($file);
}