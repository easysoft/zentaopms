#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getUpload();
cid=0

- 测试获取上传的文件信息1 @2
- 测试获取上传的文件信息2 @1
- 测试获取上传的文件信息3 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = new fileTest();

$fileNames   = array('file1.jpg', 'file2.txt');
$fileSizes   = array(1888573, 2384);
$fileTmpName = array('/tmp/phpus8Ebc', '/tmp/phpwNzwuS');
$files1      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName, 'error' => 0);
$labels1     = array('file1.jpg', 'file2.txt');

$files2      = array('name' => 'file3.ppt', 'size' => '2893', 'tmp_name' => '/tmp/phpu2el', 'error' => 0);
$labels2     = array('file3.ppt');

$fileNames   = array('file5.pptx');
$fileSizes   = array(28789);
$fileTmpName = array('/tmp/phpu2e9uS');
$files3      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName, 'error' => 0);
$labels3     = array('file5');

r($file->getCountTest($files1, $labels1)) && p() && e('2');  // 测试获取上传的文件信息1
r($file->getCountTest($files2, $labels2)) && p() && e('1');  // 测试获取上传的文件信息2
r($file->getCountTest($files3, $labels3)) && p() && e('1');  // 测试获取上传的文件信息3
