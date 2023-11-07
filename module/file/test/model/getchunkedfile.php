#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

/**

title=测试 fileModel->getChunkedFile();
cid=1
pid=1

测试获取上传image1时的文件信息 >> png,img1,3021,/tmp/phpsjisk
测试获取上传image2时的文件信息 >> png,img2,3281,/tmp/phpsjisk
测试获取上传image3时的文件信息 >> png,img3,938,/tmp/phpsjisk
测试获取上传image4时的文件信息 >> png,img4,4821,/tmp/phpsjisk
测试获取上传image5时的文件信息 >> png,img5,9870,/tmp/phpsjisk

*/

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
