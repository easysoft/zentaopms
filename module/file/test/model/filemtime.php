#!/usr/bin/env php
<?php

/**

title=测试 fileModel::fileMTime();
timeout=0
cid=16500

- 步骤1：传入空对象 @0
- 步骤2：传入存在的文件对象 @1
- 步骤3：传入不存在的文件路径 @0
- 步骤4：传入空字符串的realPath @0
- 步骤5：传入null作为realPath @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$fileTest = new fileTest();

// 4. 准备测试数据
$testFile = dirname(__FILE__) . '/test_filemtime.txt';
$testContent = 'This is a test file for fileMTime method testing.';
file_put_contents($testFile, $testContent);
$actualMTime = filemtime($testFile);

// 创建不同的文件对象用于测试
$validFile = new stdclass();
$validFile->realPath = $testFile;

$emptyFile = new stdclass();

$missingRealPathFile = new stdclass();
$missingRealPathFile->someOtherProperty = 'test';

$nonexistentFile = new stdclass();
$nonexistentFile->realPath = dirname(__FILE__) . '/nonexistent_file.txt';

$emptyPathFile = new stdclass();
$emptyPathFile->realPath = '';

$nullPathFile = new stdclass();
$nullPathFile->realPath = null;

// 5. 执行测试步骤（至少5个）
r($fileTest->fileMTimeTest($emptyFile)) && p() && e('0'); // 步骤1：传入空对象
r($fileTest->fileMTimeTest($validFile) > 0) && p() && e('1'); // 步骤2：传入存在的文件对象
r($fileTest->fileMTimeTest($nonexistentFile)) && p() && e('0'); // 步骤3：传入不存在的文件路径
r($fileTest->fileMTimeTest($emptyPathFile)) && p() && e('0'); // 步骤4：传入空字符串的realPath
r($fileTest->fileMTimeTest($nullPathFile)) && p() && e('0'); // 步骤5：传入null作为realPath

// 清理测试文件
if(file_exists($testFile)) unlink($testFile);