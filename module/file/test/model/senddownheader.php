#!/usr/bin/env php
<?php

/**

title=测试 fileModel::sendDownHeader();
timeout=0
cid=16531

- 执行fileTest模块的sendDownHeaderTest方法，参数是'document.pdf', 'pdf', 'test content', 'content'  @test content
- 执行fileTest模块的sendDownHeaderTest方法，参数是'test.txt', 'txt', $testFile, 'file'  @file_success
- 执行fileTest模块的sendDownHeaderTest方法，参数是'malicious.txt', 'txt', '/etc/passwd', 'file'  @security_denied
- 执行fileTest模块的sendDownHeaderTest方法，参数是'document', 'pdf', 'test content', 'content'  @test content
- 执行fileTest模块的sendDownHeaderTest方法，参数是'测试文件.txt', 'txt', 'safari test', 'content'  @safari test
- 执行fileTest模块的sendDownHeaderTest方法，参数是'image.jpg', 'jpg', 'image data', 'content'  @image data
- 执行fileTest模块的sendDownHeaderTest方法，参数是'', 'txt', 'empty name test', 'content'  @empty name test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

su('admin');

$fileTest = new fileTest();

// 测试步骤1：测试内容类型下载正常文件名
r($fileTest->sendDownHeaderTest('document.pdf', 'pdf', 'test content', 'content')) && p() && e('test content');

// 测试步骤2：测试文件类型下载有效文件路径
$testFile = dirname(__FILE__) . '/temp_test.txt';
file_put_contents($testFile, 'file content test');
r($fileTest->sendDownHeaderTest('test.txt', 'txt', $testFile, 'file')) && p() && e('file_success');

// 测试步骤3：测试无效文件路径安全检查
r($fileTest->sendDownHeaderTest('malicious.txt', 'txt', '/etc/passwd', 'file')) && p() && e('security_denied');

// 测试步骤4：测试文件名扩展名自动添加
r($fileTest->sendDownHeaderTest('document', 'pdf', 'test content', 'content')) && p() && e('test content');

// 测试步骤5：测试Safari浏览器文件名编码
$_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
$_SERVER['HTTP_USER_AGENT'] = 'Safari/537.36';
r($fileTest->sendDownHeaderTest('测试文件.txt', 'txt', 'safari test', 'content')) && p() && e('safari test');

// 测试步骤6：测试不同MIME类型内容头设置
r($fileTest->sendDownHeaderTest('image.jpg', 'jpg', 'image data', 'content')) && p() && e('image data');

// 测试步骤7：测试空文件名边界值处理
r($fileTest->sendDownHeaderTest('', 'txt', 'empty name test', 'content')) && p() && e('empty name test');

// 清理测试文件
if(file_exists($testFile)) unlink($testFile);