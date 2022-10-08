#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getCount();
cid=1
pid=1

测试 获取files 的个数 1 >> 2
测试 获取files 的个数 2 >> 4
测试 获取files 的个数 3 >> 5
测试 获取files 的个数 4 >> 7
测试 获取files 的个数 5 >> 10

*/
$file = new fileTest();

$fileNames   = array('file1.jpg', 'file2.txt');
$fileSizes   = array(1888573, 2384);
$fileTmpName = array('/tmp/phpus8Ebc', '/tmp/phpwNzwuS');
$files       = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels      = array('file1.jpg', 'file2.txt');
r($file->getCountTest($files, $labels)) && p() && e('2'); // 测试 获取files 的个数 1

$fileNames   = array_merge($fileNames, array('file3.ppt', 'file4.mp4'));
$fileSizes   = array_merge($fileSizes, array(2893, 34789));
$fileTmpName = array_merge($fileTmpName, array('/tmp/phpu2elbc', '/tmp/phpwje9uS'));
$files       = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels      = array_merge($labels, array('file3.ppt', 'file4.mp4'));
r($file->getCountTest($files, $labels)) && p() && e('4'); // 测试 获取files 的个数 2

$fileNames   = array_merge($fileNames, array('file5.pptx'));
$fileSizes   = array_merge($fileSizes, array(28789));
$fileTmpName = array_merge($fileTmpName, array('/tmp/phpu2e9uS'));
$files       = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels      = array_merge($labels, array('file5.pptx'));
r($file->getCountTest($files, $labels)) && p() && e('5'); // 测试 获取files 的个数 3

$fileNames   = array_merge($fileNames, array('file6.ppt', 'file7.mp4'));
$fileSizes   = array_merge($fileSizes, array(893, 23089));
$fileTmpName = array_merge($fileTmpName, array('/tmp/phpu2elbc', '/tmp/phpwje9uS'));
$files       = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels      = array_merge($labels, array('file6.ppt', 'file7.mp4'));
r($file->getCountTest($files, $labels)) && p() && e('7'); // 测试 获取files 的个数 4

$fileNames   = array_merge($fileNames, array('file9.ppt', 'file10.mp4', 'file11.wri'));
$fileSizes   = array_merge($fileSizes, array(2893, 389, 293838));
$fileTmpName = array_merge($fileTmpName, array('/tmp/phpu2ssbc', '/tmp/phpe0e9uS', '/tmp/phpjf93jf9'));
$files       = array('name' => $fileNames, 'size' => $fileSizes, 'tmp_name' => $fileTmpName);
$labels      = array_merge($labels, array('file9.ppt', 'file10.mp4', 'file11.wri'));
r($file->getCountTest($files, $labels)) && p() && e('10'); // 测试 获取files 的个数 5