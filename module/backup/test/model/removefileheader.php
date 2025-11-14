#!/usr/bin/env php
<?php

/**

title=测试 backupModel::removeFileHeader();
timeout=0
cid=15140

- 执行backupTest模块的removeFileHeaderTest方法，参数是$testFile1  @1
- 执行$testFile1), 'data1') !== false @1
- 执行backupTest模块的removeFileHeaderTest方法，参数是$testFile2  @1
- 执行$testFile2), 'CREATE TABLE') !== false @1
- 执行backupTest模块的removeFileHeaderTest方法，参数是$testFile3  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

su('admin');

$backupTest = new backupTest();

// 测试步骤1：正常移除PHP文件头
$testFile1 = dirname(__FILE__) . DS . 'test_php_header.txt';
file_put_contents($testFile1, "<?php die();?>\ndata1\ndata2");
r($backupTest->removeFileHeaderTest($testFile1)) && p() && e(1);

// 测试步骤2：验证移除后的文件内容正确
r(strpos(file_get_contents($testFile1), 'data1') !== false) && p() && e(1);

// 测试步骤3：移除SQL注释文件头
$testFile2 = dirname(__FILE__) . DS . 'test_sql_header.txt';
file_put_contents($testFile2, "-- MySQL dump\nCREATE TABLE users;\nINSERT INTO users VALUES (1);");
r($backupTest->removeFileHeaderTest($testFile2)) && p() && e(1);

// 测试步骤4：验证SQL文件移除后内容正确
r(strpos(file_get_contents($testFile2), 'CREATE TABLE') !== false) && p() && e(1);

// 测试步骤5：移除多行内容文件的首行
$testFile3 = dirname(__FILE__) . DS . 'test_multiline.txt';
file_put_contents($testFile3, "First Line\nSecond Line\nThird Line");
r($backupTest->removeFileHeaderTest($testFile3)) && p() && e(1);

// 清理测试文件
if(file_exists($testFile1)) unlink($testFile1);
if(file_exists($testFile2)) unlink($testFile2);
if(file_exists($testFile3)) unlink($testFile3);