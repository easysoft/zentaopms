#!/usr/bin/env php
<?php

/**

title=测试 backupZen::restoreFile();
timeout=0
cid=15148

- 测试不存在的备份文件属性result @success
- 测试普通备份文件还原属性result @success
- 测试带php扩展的备份文件还原属性result @success
- 测试空文件名属性result @success
- 测试特殊命名的备份文件属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$backupTest = new backupZenTest();

// 获取备份路径用于创建测试数据
global $tester;
$backupPath = $tester->loadModel('backup')->getBackupPath();

// 清理可能存在的测试文件
$testFiles = array('test_nonexist', 'test_normal', 'test_withphp', 'test_special');
foreach($testFiles as $testFile)
{
    $fileBackup = $backupPath . $testFile . '.file';
    $phpBackup  = $backupPath . $testFile . '.file.php';
    if(is_dir($fileBackup)) rmdir($fileBackup);
    if(is_dir($phpBackup)) rmdir($phpBackup);
}

// 测试步骤1：还原不存在的备份文件
r($backupTest->restoreFileTest('test_nonexist')) && p('result') && e('success'); // 测试不存在的备份文件

// 测试步骤2：创建并还原普通备份文件目录
$normalBackup = $backupPath . 'test_normal.file';
if(!is_dir($normalBackup)) mkdir($normalBackup, 0777, true);
r($backupTest->restoreFileTest('test_normal')) && p('result') && e('success'); // 测试普通备份文件还原

// 测试步骤3：创建并还原带php扩展的备份文件目录
$phpBackup = $backupPath . 'test_withphp.file.php';
if(!is_dir($phpBackup)) mkdir($phpBackup, 0777, true);
r($backupTest->restoreFileTest('test_withphp')) && p('result') && e('success'); // 测试带php扩展的备份文件还原

// 测试步骤4：测试还原空文件名
r($backupTest->restoreFileTest('')) && p('result') && e('success'); // 测试空文件名

// 测试步骤5：测试还原特殊命名的备份文件
$specialBackup = $backupPath . 'test_special.file';
if(!is_dir($specialBackup)) mkdir($specialBackup, 0777, true);
r($backupTest->restoreFileTest('test_special')) && p('result') && e('success'); // 测试特殊命名的备份文件

// 清理测试文件
foreach($testFiles as $testFile)
{
    $fileBackup = $backupPath . $testFile . '.file';
    $phpBackup  = $backupPath . $testFile . '.file.php';
    if(is_dir($fileBackup)) rmdir($fileBackup);
    if(is_dir($phpBackup)) rmdir($phpBackup);
}