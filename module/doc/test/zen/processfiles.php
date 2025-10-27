#!/usr/bin/env php
<?php

/**

title=测试 docZen::processFiles();
timeout=0
cid=0

- 步骤1：文件名去除扩展名测试第1条的fileName属性 @test1
- 步骤2：文件大小格式化测试第1条的sizeText属性 @1.0K
- 步骤3：fileIcon映射测试第1条的fileIcon属性 @icon-text
- 步骤4：空pathname文件被过滤属性2 @~~
- 步骤5：sourcePairs映射测试第3条的sourceName属性 @需求3
- 步骤6：requirement对象类型特殊处理第4条的objectName属性 @用户需求 :
- 步骤7：skipImageWidth参数测试第5条的sizeText属性 @4.0K

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('file');
$table->id->range('1-10');
$table->pathname->range('/files/test1.txt,/files/test2.doc,,/files/image.jpg,/files/doc.docx');
$table->title->range('test1.txt,test2.doc,empty_file.txt,image.jpg,document.docx');
$table->extension->range('txt,doc,txt,jpg,docx');
$table->size->range('1024,2048,0,51200,10240');
$table->objectType->range('doc,product,story,story,requirement');
$table->objectID->range('1,2,3,4,5');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 准备测试数据
$files = array();

// 文件1：正常文件
$file1 = new stdClass();
$file1->id = 1;
$file1->pathname = '/files/test1.txt';
$file1->title = 'test1.txt';
$file1->extension = 'txt';
$file1->size = 1024;
$file1->objectType = 'doc';
$file1->objectID = 1;
$files[1] = $file1;

// 文件2：空pathname文件（将被过滤）
$file2 = new stdClass();
$file2->id = 2;
$file2->pathname = '';
$file2->title = 'empty_file.txt';
$file2->extension = 'txt';
$file2->size = 0;
$file2->objectType = 'product';
$file2->objectID = 2;
$files[2] = $file2;

// 文件3：图片文件
$file3 = new stdClass();
$file3->id = 3;
$file3->pathname = '/files/image.jpg';
$file3->title = 'image.jpg';
$file3->extension = 'jpg';
$file3->size = 51200;
$file3->objectType = 'story';
$file3->objectID = 3;
$files[3] = $file3;

// 文件4：requirement类型文件
$file4 = new stdClass();
$file4->id = 4;
$file4->pathname = '/files/doc.docx';
$file4->title = 'document.docx';
$file4->extension = 'docx';
$file4->size = 10240;
$file4->objectType = 'requirement';
$file4->objectID = 4;
$files[4] = $file4;

// 文件5：其他类型文件
$file5 = new stdClass();
$file5->id = 5;
$file5->pathname = '/files/test5.zip';
$file5->title = 'test5.zip';
$file5->extension = 'zip';
$file5->size = 4096;
$file5->objectType = 'task';
$file5->objectID = 5;
$files[5] = $file5;

// fileIcon映射数据
$fileIcon = array(
    1 => 'icon-text',
    3 => 'icon-image',
    4 => 'icon-doc'
);

// sourcePairs映射数据
$sourcePairs = array(
    'doc' => array(1 => '文档1'),
    'story' => array(3 => '需求3'),
    'requirement' => array(4 => '用户需求4'),
    'task' => array(5 => '任务5')
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, false)) && p('1:fileName') && e('test1'); // 步骤1：文件名去除扩展名测试
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, false)) && p('1:sizeText') && e('1.0K'); // 步骤2：文件大小格式化测试
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, false)) && p('1:fileIcon') && e('icon-text'); // 步骤3：fileIcon映射测试
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, false)) && p('2') && e('~~'); // 步骤4：空pathname文件被过滤
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, false)) && p('3:sourceName') && e('需求3'); // 步骤5：sourcePairs映射测试
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, false)) && p('4:objectName') && e('用户需求 : '); // 步骤6：requirement对象类型特殊处理
r($docTest->processFilesTest($files, $fileIcon, $sourcePairs, true)) && p('5:sizeText') && e('4.0K'); // 步骤7：skipImageWidth参数测试