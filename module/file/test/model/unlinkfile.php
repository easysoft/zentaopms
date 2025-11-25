#!/usr/bin/env php
<?php

/**

title=测试 fileModel::unlinkFile();
timeout=0
cid=16537

- 执行fileTest模块的unlinkFileTest方法，参数是$file1  @1
- 执行fileTest模块的unlinkFileTest方法，参数是$file2  @0
- 执行fileTest模块的unlinkFileTest方法，参数是$file3  @0
- 执行fileTest模块的unlinkFileTest方法，参数是$file4  @0
- 执行fileTest模块的unlinkFileTest方法，参数是$file5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

su('admin');

$fileTest = new fileTest();

// 准备测试文件路径
$testDir = dirname(__FILE__) . '/temp/';
if(!is_dir($testDir)) mkdir($testDir, 0777, true);

$existingFile = $testDir . 'existing_file.txt';
$nonExistentFile = $testDir . 'non_existent_file.txt';
$invalidPathFile = '/invalid/path/file.txt';

// 创建存在的文件
touch($existingFile);
file_put_contents($existingFile, 'test content');

// 步骤1：测试删除存在的文件
$file1 = new stdclass();
$file1->realPath = $existingFile;
r($fileTest->unlinkFileTest($file1)) && p() && e('1');

// 步骤2：测试删除不存在的文件
$file2 = new stdclass();
$file2->realPath = $nonExistentFile;
r($fileTest->unlinkFileTest($file2)) && p() && e('0');

// 步骤3：测试传入空realPath的文件对象
$file3 = new stdclass();
r($fileTest->unlinkFileTest($file3)) && p() && e('0');

// 步骤4：测试传入无效路径的文件对象
$file4 = new stdclass();
$file4->realPath = $invalidPathFile;
r($fileTest->unlinkFileTest($file4)) && p() && e('0');

// 步骤5：测试传入空对象
$file5 = new stdclass();
$file5->realPath = '';
r($fileTest->unlinkFileTest($file5)) && p() && e('0');

// 清理测试文件和目录
if(file_exists($existingFile)) unlink($existingFile);
if(is_dir($testDir)) rmdir($testDir);