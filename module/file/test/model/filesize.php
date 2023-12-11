#!/usr/bin/env php
<?php

/**

title=测试 fileModel->fileSize();
timeout=0
cid=0

- 传入空对象。 @0
- 检查文件的大小。 @4
- 文件不存在 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$downloadFile = dirname(__FILE__) . '/download.txt';
file_put_contents($downloadFile, 'test');

$file = new stdclass();
$file->realPath = $downloadFile;

global $tester;
$fileModel = $tester->loadModel('file');

r($fileModel->fileSize(new stdclass())) && p() && e('0'); //传入空对象。
r($fileModel->fileSize($file))          && p() && e('4'); //检查文件的大小。

$file->realPath = dirname(__FILE__) . '/download1.txt';
r($fileModel->fileSize($file)) && p() && e('0'); //文件不存在

unlink($downloadFile);