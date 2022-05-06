#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getUpload();
cid=1
pid=1

测试获取上传的文件信息1 >> file1.jpg;file2.txt
测试获取上传的文件信息2 >> file3.ppt;file4.mp4
测试获取上传的文件信息3 >> file5.pptx
测试获取上传的文件信息4 >> file6.ppt;file7.mp4
测试获取上传的文件信息5 >> file9.ppt;file10.mp4;file11.wri

*/

$file = new fileTest();

$fileNames   = array('file1.jpg', 'file2.txt');
$fileSizes   = array(1888573, 2384);
$fileTmpName = array('/tmp/phpus8Ebc', '/tmp/phpwNzwuS');
$files1      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels1     = array('file1.jpg', 'file2.txt');

$fileNames   = array('file3.ppt', 'file4.mp4');
$fileSizes   = array(2893, 34789);
$fileTmpName = array('/tmp/phpu2elbc', '/tmp/phpwje9uS');
$files2      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels2     = array('file3.ppt', 'file4.mp4');

$fileNames   = array('file5.pptx');
$fileSizes   = array(28789);
$fileTmpName = array('/tmp/phpu2e9uS');
$files3      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels3     = array('file5.pptx');

$fileNames   = array('file6.ppt', 'file7.mp4');
$fileSizes   = array(893, 23089);
$fileTmpName = array('/tmp/phpu2elbc', '/tmp/phpwje9uS');
$files4      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels4     = array('file6.ppt', 'file7.mp4');

$fileNames   = array('file9.ppt', 'file10.mp4', 'file11.wri');
$fileSizes   = array(2893, 389, 293838);
$fileTmpName = array('/tmp/phpu2ssbc', '/tmp/phpe0e9uS', '/tmp/phpjf93jf9');
$files5      = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels5     = array('file9.ppt', 'file10.mp4', 'file11.wri');

r($file->getUploadTest($files1, $labels1)) && p('0:title;1:title')         && e('file1.jpg;file2.txt');             // 测试获取上传的文件信息1
r($file->getUploadTest($files2, $labels2)) && p('0:title;1:title')         && e('file3.ppt;file4.mp4');             // 测试获取上传的文件信息2
r($file->getUploadTest($files3, $labels3)) && p('0:title')                 && e('file5.pptx');                      // 测试获取上传的文件信息3
r($file->getUploadTest($files4, $labels4)) && p('0:title;1:title')         && e('file6.ppt;file7.mp4');             // 测试获取上传的文件信息4
r($file->getUploadTest($files5, $labels5)) && p('0:title;1:title;2:title') && e('file9.ppt;file10.mp4;file11.wri'); // 测试获取上传的文件信息5