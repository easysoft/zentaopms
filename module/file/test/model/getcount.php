#!/usr/bin/env php
<?php

/**

title=测试 fileModel::getCount();
timeout=0
cid=16507

- 测试多文件上传时获取文件数量 @2
- 测试单文件上传时获取文件数量 @1
- 测试空文件名时获取文件数量 @1
- 测试无上传文件时获取文件数量 @4
- 测试上传错误时获取文件数量 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$file = new fileModelTest();

// 测试数据1：多文件上传
$fileNames1   = array('file1.jpg', 'file2.txt');
$fileSizes1   = array(1888573, 2384);
$fileTmpName1 = array('/tmp/phpus8Ebc', '/tmp/phpwNzwuS');
$files1       = array('name' => $fileNames1, 'size' => $fileSizes1, 'tmp_name' => $fileTmpName1, 'error' => 0);
$labels1      = array('file1.jpg', 'file2.txt');

// 测试数据2：单文件上传
$files2 = array('name' => 'file3.ppt', 'size' => '2893', 'tmp_name' => '/tmp/phpu2el', 'error' => 0);
$labels2 = array('file3.ppt');

// 测试数据3：包含空文件名的多文件上传
$fileNames3   = array('file5.pptx', '');
$fileSizes3   = array(28789, 0);
$fileTmpName3 = array('/tmp/phpu2e9uS', '');
$files3       = array('name' => $fileNames3, 'size' => $fileSizes3, 'tmp_name' => $fileTmpName3, 'error' => 0);
$labels3      = array('file5', '');

// 测试数据4：无上传文件
$files4 = array('name' => '', 'size' => 0, 'tmp_name' => '', 'error' => 4);
$labels4 = array();

// 测试数据5：上传错误
$files5 = array('name' => 'error.txt', 'size' => 1024, 'tmp_name' => '/tmp/error', 'error' => 1);
$labels5 = array('error.txt');

r($file->getCountTest($files1, $labels1)) && p() && e('2');  // 测试多文件上传时获取文件数量
r($file->getCountTest($files2, $labels2)) && p() && e('1');  // 测试单文件上传时获取文件数量
r($file->getCountTest($files3, $labels3)) && p() && e('1');  // 测试空文件名时获取文件数量
r($file->getCountTest($files4, $labels4)) && p() && e('4');  // 测试无上传文件时获取文件数量
r($file->getCountTest($files5, $labels5)) && p() && e('4');  // 测试上传错误时获取文件数量