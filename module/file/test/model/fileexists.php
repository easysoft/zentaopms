#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

/**

title=测试 fileModel->fileExists();
cid=1
pid=1

*/

global $tester;
$fileModel = $tester->loadModel('file');

$file = new stdclass();
$file->realPath = dirname(__FILE__) . '/test.txt';

r($fileModel->fileExists($file)) && p() && e('0'); //文件不存在

touch($file->realPath);
r($fileModel->fileExists($file)) && p() && e('1'); //创建文件

unlink($file->realPath);

