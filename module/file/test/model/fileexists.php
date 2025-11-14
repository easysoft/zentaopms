#!/usr/bin/env php
<?php

/**

title=测试 fileModel::fileExists();
timeout=0
cid=16499

- 执行fileModel模块的fileExists方法，参数是$emptyFile  @0
- 执行fileModel模块的fileExists方法，参数是$fileWithoutRealPath  @0
- 执行fileModel模块的fileExists方法，参数是$fileWithEmptyPath  @0
- 执行fileModel模块的fileExists方法，参数是$fileWithNonExistingPath  @0
- 执行fileModel模块的fileExists方法，参数是$fileWithExistingPath  @1
- 执行fileModel模块的fileExists方法，参数是$fileWithNullPath  @0
- 执行fileModel模块的fileExists方法，参数是$fileWithSpecialCharPath  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$fileModel = $tester->loadModel('file');

// 准备测试文件路径
$testDir = dirname(__FILE__);
$existingFile = $testDir . '/test_existing.txt';
$nonExistingFile = $testDir . '/test_nonexisting.txt';
$specialCharFile = $testDir . '/test_特殊字符文件.txt';

// 创建存在的测试文件
touch($existingFile);
touch($specialCharFile);

// 测试步骤1：传入空对象
$emptyFile = new stdclass();
r($fileModel->fileExists($emptyFile)) && p() && e('0');

// 测试步骤2：传入无realPath属性的对象
$fileWithoutRealPath = new stdclass();
$fileWithoutRealPath->id = 1;
$fileWithoutRealPath->title = 'test.txt';
r($fileModel->fileExists($fileWithoutRealPath)) && p() && e('0');

// 测试步骤3：传入realPath为空字符串的对象
$fileWithEmptyPath = new stdclass();
$fileWithEmptyPath->realPath = '';
r($fileModel->fileExists($fileWithEmptyPath)) && p() && e('0');

// 测试步骤4：传入realPath指向不存在文件的对象
$fileWithNonExistingPath = new stdclass();
$fileWithNonExistingPath->realPath = $nonExistingFile;
r($fileModel->fileExists($fileWithNonExistingPath)) && p() && e('0');

// 测试步骤5：传入realPath指向存在文件的对象
$fileWithExistingPath = new stdclass();
$fileWithExistingPath->realPath = $existingFile;
r($fileModel->fileExists($fileWithExistingPath)) && p() && e('1');

// 测试步骤6：传入realPath为null的对象
$fileWithNullPath = new stdclass();
$fileWithNullPath->realPath = null;
r($fileModel->fileExists($fileWithNullPath)) && p() && e('0');

// 测试步骤7：传入realPath包含特殊字符的路径
$fileWithSpecialCharPath = new stdclass();
$fileWithSpecialCharPath->realPath = $specialCharFile;
r($fileModel->fileExists($fileWithSpecialCharPath)) && p() && e('1');

// 清理测试文件
unlink($existingFile);
unlink($specialCharFile);