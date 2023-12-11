#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getRealPathName();
cid=0

- 获取 202205/1.txt 的实际存储路径 @202205/1
- 获取 202204/2.png 的实际存储路径 @202204/2
- 获取 202203/3.mp4 的实际存储路径 @202203/3
- 获取 202202/4.zip 的实际存储路径 @202202/4
- 获取 202201/5 的实际存储路径 @202201/5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$pathnames = array('202205/1.txt', '202204/2.png', '202203/3.mp4', '202202/4.zip', '202201/5');

$file = new fileTest();

r($file->getRealPathNameTest($pathnames[0])) && p() && e('202205/1'); // 获取 202205/1.txt 的实际存储路径
r($file->getRealPathNameTest($pathnames[1])) && p() && e('202204/2'); // 获取 202204/2.png 的实际存储路径
r($file->getRealPathNameTest($pathnames[2])) && p() && e('202203/3'); // 获取 202203/3.mp4 的实际存储路径
r($file->getRealPathNameTest($pathnames[3])) && p() && e('202202/4'); // 获取 202202/4.zip 的实际存储路径
r($file->getRealPathNameTest($pathnames[4])) && p() && e('202201/5'); // 获取 202201/5 的实际存储路径
