#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getExtension();
cid=0

- 获取 1.xlsm 的扩展名 @xlsm
- 获取 2.jpg 的扩展名 @jpg
- 获取 3.ppt 的扩展名 @txt
- 获取 4.txt 的扩展名 @mp4
- 获取 5.zip 的扩展名 @zip
- 获取 1.php 的扩展名 @txt
- 获取 2.phtml 的扩展名 @txt
- 获取 3.jsp 的扩展名 @txt
- 获取 4.py 的扩展名 @txt
- 获取 5.abc 的扩展名 @txt

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$fileNames      = array('1.xlsm', '2.jpg', '3.txt', '4.mp4::mp4', '5.zip::zip');
$errorFileNames = array('1.php', '2.phtml', '3.jsp', '4.py', '5.abc');

$file = new fileTest();

r($file->getExtensionTest($fileNames[0]))      && p() && e('xlsm'); // 获取 1.xlsm 的扩展名
r($file->getExtensionTest($fileNames[1]))      && p() && e('jpg');  // 获取 2.jpg 的扩展名
r($file->getExtensionTest($fileNames[2]))      && p() && e('txt');  // 获取 3.ppt 的扩展名
r($file->getExtensionTest($fileNames[3]))      && p() && e('mp4');  // 获取 4.txt 的扩展名
r($file->getExtensionTest($fileNames[4]))      && p() && e('zip');  // 获取 5.zip 的扩展名
r($file->getExtensionTest($errorFileNames[0])) && p() && e('txt');  // 获取 1.php 的扩展名
r($file->getExtensionTest($errorFileNames[1])) && p() && e('txt');  // 获取 2.phtml 的扩展名
r($file->getExtensionTest($errorFileNames[2])) && p() && e('txt');  // 获取 3.jsp 的扩展名
r($file->getExtensionTest($errorFileNames[3])) && p() && e('txt');  // 获取 4.py 的扩展名
r($file->getExtensionTest($errorFileNames[4])) && p() && e('txt');  // 获取 5.abc 的扩展名
