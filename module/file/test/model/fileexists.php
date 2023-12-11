#!/usr/bin/env php
<?php

/**

title=测试 fileModel->fileExists();
timeout=0
cid=0

- 传入空对象 @0
- 文件不存在 @0
- 创建文件 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$fileModel = $tester->loadModel('file');

$file = new stdclass();
$file->realPath = dirname(__FILE__) . '/test.txt';

r($fileModel->fileExists(new stdclass())) && p() && e('0'); //传入空对象
r($fileModel->fileExists($file)) && p() && e('0');          //文件不存在

touch($file->realPath);
r($fileModel->fileExists($file)) && p() && e('1'); //创建文件

unlink($file->realPath);