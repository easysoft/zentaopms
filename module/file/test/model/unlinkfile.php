#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

/**

title=测试 fileModel->unlinkFile();
cid=1
pid=1

*/

$downloadFile = dirname(__FILE__) . '/download.txt';
touch($downloadFile);

$file = new stdclass();
$file->realPath = $downloadFile;

global $tester;
$fileModel = $tester->loadModel('file');

$fileModel->unlinkFile($file);
r(!file_exists($downloadFile)) && p() && e('1'); //检查文件是否删除

unlink($downloadFile);
