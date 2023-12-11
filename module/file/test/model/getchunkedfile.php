#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getChunkedFile();
cid=1

- 测试获取上传分片的文件信息
 - 属性extension @png
 - 属性title @a
 - 属性size @3021
 - 属性pathname @0cc175b9c0f1b6a831c399e269772661
 - 属性chunks @2
 - 属性chunkIndex @0
- 测试获取上传空文件名 @0
- 测试获取不合法的文件名 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$_SERVER['HTTP_X_FILENAME']     = 'a.png';
$_SERVER['HTTP_X_FILESIZE']     = '3021';
$_SERVER['HTTP_X_TOTAL_CHUNKS'] = 2;
$_SERVER['HTTP_X_CHUNK_INDEX']  = 0;

global $tester;
$fileModel = $tester->loadModel('file');

r($fileModel->getChunkedFile()) && p('extension,title,size,pathname,chunks,chunkIndex') && e('png,a,3021,0cc175b9c0f1b6a831c399e269772661,2,0'); // 测试获取上传分片的文件信息

$_SERVER['HTTP_X_FILENAME'] = '';
r($fileModel->getChunkedFile()) && p() && e('0'); // 测试获取上传空文件名

$_SERVER['HTTP_X_FILENAME'] = '<script>alert(111)</script>.png';
r($fileModel->getChunkedFile()) && p() && e('0'); // 测试获取不合法的文件名
