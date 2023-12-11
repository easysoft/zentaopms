#!/usr/bin/env php
<?php

/**

title=测试 fileModel->saveAsTempFile();
cid=0

- 传入空对象。 @0
- 检查文件路径。 @1
- 文件储存类型为s3。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$downloadFile = dirname(__FILE__) . '/download.txt';

$file = new stdclass();
$file->realPath = $downloadFile;

global $tester;
$fileModel = $tester->loadModel('file');
$fileModel->config->file->storageType = 'fs';

r($fileModel->saveAsTempFile(new stdclass()))         && p() && e('0'); //传入空对象。
r($fileModel->saveAsTempFile($file) == $downloadFile) && p() && e('1'); //检查文件路径。

$fileModel->config->file->storageType = 's3';
r($fileModel->saveAsTempFile($file)) && p() && e('0'); //文件储存类型为s3。
