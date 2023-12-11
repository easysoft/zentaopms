#!/usr/bin/env php
<?php

/**

title=测试 fileModel->unlinkFile();
timeout=0
cid=0

- 传入空对象。 @0
- 检查文件是否删除 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$downloadFile = dirname(__FILE__) . '/download.txt';
touch($downloadFile);

$file = new stdclass();
$file->realPath = $downloadFile;

global $tester;
$fileModel = $tester->loadModel('file');

$fileModel->unlinkFile($file);
r($fileModel->unlinkFile(new stdclass())) && p() && e('0'); //传入空对象。
r(!file_exists($downloadFile))            && p() && e('1'); //检查文件是否删除

unlink($downloadFile);