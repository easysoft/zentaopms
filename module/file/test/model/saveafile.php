#!/usr/bin/env php
<?php

/**

title=测试 fileModel::saveAFile();
timeout=0
cid=16525

- 测试步骤1：正常保存有效文件属性title @有效测试文件.txt
- 测试步骤2：保存图片文件测试压缩属性title @测试图片.jpg
- 测试步骤3：无效临时文件路径 @0
- 测试步骤4：大文件处理能力测试属性title @大文件测试.doc
- 测试步骤5：空文件处理测试属性title @空文件测试.txt

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

$file = zenData('file');
$file->id->range('1-100');
$file->gen(5);

$fileTest = new fileTest();

$validFile = array(
    'pathname' => '202509/test_valid_file.txt',
    'title' => '有效测试文件.txt',
    'extension' => 'txt',
    'size' => 1024,
    'tmpname' => 'valid_temp_file'
);

$imageFile = array(
    'pathname' => '202509/test_image.jpg',
    'title' => '测试图片.jpg',
    'extension' => 'jpg',
    'size' => 51200,
    'tmpname' => 'image_temp_file'
);

$invalidFile = array(
    'pathname' => '202509/test_invalid.txt',
    'title' => '无效文件.txt',
    'extension' => 'txt',
    'size' => 512,
    'tmpname' => '/nonexistent/path/invalid_temp_file'
);

$largeFile = array(
    'pathname' => '202509/test_large_file.doc',
    'title' => '大文件测试.doc',
    'extension' => 'doc',
    'size' => 1048576,
    'tmpname' => 'large_temp_file'
);

$emptyFile = array(
    'pathname' => '202509/test_empty.txt',
    'title' => '空文件测试.txt',
    'extension' => 'txt',
    'size' => 0,
    'tmpname' => 'empty_temp_file'
);

r($fileTest->saveAFileTest($validFile, 'story', 101, 'test')) && p('title') && e('有效测试文件.txt'); // 测试步骤1：正常保存有效文件
r($fileTest->saveAFileTest($imageFile, 'task', 102, 'image')) && p('title') && e('测试图片.jpg'); // 测试步骤2：保存图片文件测试压缩
r($fileTest->saveAFileTest($invalidFile, 'bug', 103, 'invalid')) && p() && e('0'); // 测试步骤3：无效临时文件路径
r($fileTest->saveAFileTest($largeFile, 'story', 104, 'large')) && p('title') && e('大文件测试.doc'); // 测试步骤4：大文件处理能力测试
r($fileTest->saveAFileTest($emptyFile, 'task', 105, 'empty')) && p('title') && e('空文件测试.txt'); // 测试步骤5：空文件处理测试