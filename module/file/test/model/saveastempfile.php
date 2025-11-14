#!/usr/bin/env php
<?php

/**

title=测试 fileModel->saveAsTempFile();
timeout=0
cid=16526

- 测试传入空realPath的文件对象 @
- 测试fs存储类型下的有效文件路径 @/tmp/test.txt
- 测试s3存储类型下的文件处理 @
- 测试空字符串realPath的处理 @
- 测试其他存储类型下的文件处理 @

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

su('admin');

$fileTest = new fileTest();

// 创建测试文件对象
$validFile = new stdclass();
$validFile->realPath = '/tmp/test.txt';

$emptyFile = new stdclass();

$nullPathFile = new stdclass();
$nullPathFile->realPath = '';

// 设置文件存储类型为fs
$fileTest->objectModel->config->file->storageType = 'fs';

r($fileTest->saveAsTempFileTest($emptyFile)) && p() && e(''); // 测试传入空realPath的文件对象
r($fileTest->saveAsTempFileTest($validFile)) && p() && e('/tmp/test.txt'); // 测试fs存储类型下的有效文件路径

// 设置文件存储类型为s3
$fileTest->objectModel->config->file->storageType = 's3';
r($fileTest->saveAsTempFileTest($validFile)) && p() && e(''); // 测试s3存储类型下的文件处理

// 重新设置为fs进行更多测试
$fileTest->objectModel->config->file->storageType = 'fs';
r($fileTest->saveAsTempFileTest($nullPathFile)) && p() && e(''); // 测试空字符串realPath的处理

// 测试其他存储类型
$fileTest->objectModel->config->file->storageType = 'oss';
r($fileTest->saveAsTempFileTest($validFile)) && p() && e(''); // 测试其他存储类型下的文件处理