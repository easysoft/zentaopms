#!/usr/bin/env php
<?php

/**

title=测试 backupModel::addFileHeader();
timeout=0
cid=15128

- 执行backupTest模块的addFileHeaderTest方法，参数是$file1  @1
- 执行backupTest模块的addFileHeaderTest方法，参数是$file2  @1
- 执行$hasHeader @1
- 执行$size @24
- 执行$extractedContent @preserve this

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$backupTest = new backupModelTest();

// 准备测试目录
$testDir = dirname(__FILE__) . '/test_files/';
if(!is_dir($testDir)) mkdir($testDir, 0777, true);

// 步骤1：正常文件添加安全头测试
$file1 = $testDir . 'test1.txt';
file_put_contents($file1, 'content1');
r($backupTest->addFileHeaderTest($file1)) && p() && e('1');

// 步骤2：空文件添加安全头测试
$file2 = $testDir . 'test2.txt';
file_put_contents($file2, '');
r($backupTest->addFileHeaderTest($file2)) && p() && e('1');

// 步骤3：验证安全头是否正确添加
$file3 = $testDir . 'test3.txt';
file_put_contents($file3, 'test');
$backupTest->addFileHeaderTest($file3);
$content = file_get_contents($file3);
$hasHeader = strpos($content, '<?php die();?>') === 0;
r($hasHeader) && p() && e('1');

// 步骤4：验证文件大小正确增加
$file4 = $testDir . 'test4.txt';
file_put_contents($file4, 'size test');
$backupTest->addFileHeaderTest($file4);
$size = filesize($file4);
r($size) && p() && e('24');

// 步骤5：验证原文件内容保持完整
$file5 = $testDir . 'test5.txt';
$originalText = 'preserve this';
file_put_contents($file5, $originalText);
$backupTest->addFileHeaderTest($file5);
$newContent = file_get_contents($file5);
$extractedContent = substr($newContent, 15);
r($extractedContent) && p() && e('preserve this');

// 清理测试文件
for($i = 1; $i <= 5; $i++) {
    $file = $testDir . "test{$i}.txt";
    if(file_exists($file)) unlink($file);
}
if(is_dir($testDir)) rmdir($testDir);