#!/usr/bin/env php
<?php

/**

title=测试 fileModel->fileMTime();
timeout=0
cid=0

- 传入空对象。 @0
- 检查文件的修改时间。 @1
- 文件不存在 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$downloadFile = dirname(__FILE__) . '/download.txt';
touch($downloadFile);

$file = new stdclass();
$file->realPath = $downloadFile;

global $tester;
$fileModel = $tester->loadModel('file');

$filemtime = filemtime($file->realPath);
r($fileModel->fileMTime(new stdclass()))      && p() && e('0'); //传入空对象。
r($fileModel->fileMTime($file) == $filemtime) && p() && e('1'); //检查文件的修改时间。

$file->realPath = dirname(__FILE__) . '/download1.txt';
r($fileModel->fileMTime($file)) && p() && e('0'); //文件不存在

unlink($downloadFile);