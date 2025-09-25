#!/usr/bin/env php
<?php

/**

title=测试 aiModel::extractZtAppZip();
timeout=0
cid=0

- 步骤1：有效ZIP文件解压成功第0条的filename属性 @/home/z/repo/git/zentaopms/tmp/test.txt
- 步骤2：无效ZIP文件 @0
- 步骤3：不存在文件 @0
- 步骤4：空文件路径 @0
- 步骤5：普通文本文件 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 4. 准备测试文件
$validZipPath = '/tmp/test_valid.zip';
$invalidZipPath = '/tmp/invalid.zip';
$nonExistentPath = '/tmp/nonexistent.zip';
$emptyPath = '';
$textFilePath = '/tmp/test_text.txt';

// 创建一个有效的ZIP文件用于测试
$zip = new ZipArchive();
if ($zip->open($validZipPath, ZipArchive::CREATE) === TRUE) {
    $zip->addFromString('test.txt', 'This is a test file');
    $zip->close();
}

// 创建一个损坏的ZIP文件
file_put_contents($invalidZipPath, 'invalid zip content');

// 创建一个普通文本文件用于测试
file_put_contents($textFilePath, 'This is not a zip file');

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->extractZtAppZipTest($validZipPath)) && p('0:filename') && e('/home/z/repo/git/zentaopms/tmp/test.txt'); // 步骤1：有效ZIP文件解压成功
r($aiTest->extractZtAppZipTest($invalidZipPath)) && p() && e('0'); // 步骤2：无效ZIP文件
r($aiTest->extractZtAppZipTest($nonExistentPath)) && p() && e('0'); // 步骤3：不存在文件
r($aiTest->extractZtAppZipTest($emptyPath)) && p() && e('0'); // 步骤4：空文件路径
r($aiTest->extractZtAppZipTest($textFilePath)) && p() && e('0'); // 步骤5：普通文本文件

// 清理测试文件
@unlink($validZipPath);
@unlink($invalidZipPath);
@unlink($textFilePath);