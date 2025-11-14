#!/usr/bin/env php
<?php

/**

title=测试 fileModel->fileSize();
timeout=0
cid=16501

- 传入空对象 @0
- 检查文件的大小 @4
- 文件不存在 @0
- 空路径 @0
- 中文内容文件 @18

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$downloadFile = dirname(__FILE__) . '/download.txt';
$chineseFile = dirname(__FILE__) . '/chinese.txt';
file_put_contents($downloadFile, 'test');
file_put_contents($chineseFile, '测试中文内容');

$file = new stdclass();
$file->realPath = $downloadFile;

$chineseFileObj = new stdclass();
$chineseFileObj->realPath = $chineseFile;

global $tester;
$fileModel = $tester->loadModel('file');

r($fileModel->fileSize(new stdclass())) && p() && e('0'); //传入空对象
r($fileModel->fileSize($file)) && p() && e('4'); //检查文件的大小
$file->realPath = dirname(__FILE__) . '/download1.txt';
r($fileModel->fileSize($file)) && p() && e('0'); //文件不存在
$file->realPath = '';
r($fileModel->fileSize($file)) && p() && e('0'); //空路径
r($fileModel->fileSize($chineseFileObj)) && p() && e('18'); //中文内容文件

unlink($downloadFile);
unlink($chineseFile);