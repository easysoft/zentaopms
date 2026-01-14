#!/usr/bin/env php
<?php

/**

title=测试 fileModel::saveChunkedFile();
timeout=0
cid=16527

- 执行fileTest模块的saveChunkedFileTest方法，参数是$normalFile, $normalUid 
 - 属性extension @txt
 - 属性title @test_file
 - 属性size @1024
- 执行fileTest模块的saveChunkedFileTest方法，参数是$zeroChunkFile, $zeroUid  @0
- 执行fileTest模块的saveChunkedFileTest方法，参数是$invalidFile, $invalidUid 
 - 属性extension @doc
 - 属性title @invalid_file
- 执行fileTest模块的saveChunkedFileTest方法，参数是$specialFile, $specialUid 
 - 属性title @test_file_中文
 - 属性extension @jpg
- 执行fileTest模块的saveChunkedFileTest方法，参数是$largeFile, $largeUid 
 - 属性size @10485760
 - 属性extension @zip

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$fileTest = new fileModelTest();

// 测试步骤1：正常分块文件保存
$normalFile = array(
    'extension' => 'txt',
    'title' => 'test_file',
    'size' => 1024,
    'pathname' => 'test_file_path',
    'chunks' => 3,
    'chunkIndex' => 0
);
$normalUid = 'test_uid_001';
r($fileTest->saveChunkedFileTest($normalFile, $normalUid)) && p('extension,title,size') && e('txt,test_file,1024');

// 测试步骤2：零分块文件处理
$zeroChunkFile = array(
    'extension' => 'pdf',
    'title' => 'zero_chunk_file',
    'size' => 512,
    'pathname' => 'zero_chunk_path',
    'chunks' => 0,
    'chunkIndex' => 0
);
$zeroUid = 'test_uid_002';
r($fileTest->saveChunkedFileTest($zeroChunkFile, $zeroUid)) && p() && e('0');

// 测试步骤3：无效分块数据处理
$invalidFile = array(
    'extension' => 'doc',
    'title' => 'invalid_file',
    'size' => 2048,
    'pathname' => 'invalid_path',
    'chunks' => '',
    'chunkIndex' => 1
);
$invalidUid = 'test_uid_003';
r($fileTest->saveChunkedFileTest($invalidFile, $invalidUid)) && p('extension,title') && e('doc,invalid_file');

// 测试步骤4：特殊字符文件名处理
$specialFile = array(
    'extension' => 'jpg',
    'title' => 'test_file_中文',
    'size' => 4096,
    'pathname' => 'special_char_path',
    'chunks' => 2,
    'chunkIndex' => 1
);
$specialUid = 'test_uid_004';
r($fileTest->saveChunkedFileTest($specialFile, $specialUid)) && p('title,extension') && e('test_file_中文,jpg');

// 测试步骤5：大文件分块处理
$largeFile = array(
    'extension' => 'zip',
    'title' => 'large_file',
    'size' => 10485760,
    'pathname' => 'large_file_path',
    'chunks' => 10,
    'chunkIndex' => 5
);
$largeUid = 'test_uid_005';
r($fileTest->saveChunkedFileTest($largeFile, $largeUid)) && p('size,extension') && e('10485760,zip');