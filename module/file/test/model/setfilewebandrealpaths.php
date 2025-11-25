#!/usr/bin/env php
<?php

/**

title=测试 fileModel::setFileWebAndRealPaths();
cid=16532

- 测试步骤1：普通文件的路径设置 >> 期望正确设置realPath和webPath
- 测试步骤2：traincourse类型文件的特殊路径设置 >> 期望使用course目录路径
- 测试步骤3：traincontents类型文件的特殊路径设置 >> 期望使用course目录路径
- 测试步骤4：文件name和url属性的自动设置 >> 期望正确设置name和url
- 测试步骤5：缺失title的文件对象处理 >> 期望name属性使用title值
- 测试步骤6：验证文件下载链接的正确生成 >> 期望url包含正确的fileID参数
- 测试步骤7：空pathname的边界情况测试 >> 期望正常处理不会报错

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

$file = zenData('file');
$file->id->range('1-10');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,,202305/0414225006610011,course/lesson1.pdf,course/lesson2.mp4');
$file->title->range('普通文件1.txt,普通文件2.doc,普通文件3.pdf,普通文件4.zip,培训课程1.pdf,培训内容1.mp4,,测试文件.txt,课程资料.pdf,课程视频.mp4');
$file->objectType->range('task,bug,story,project,traincourse,traincontents,user,product,course,training');
$file->objectID->range('1-10');
$file->extension->range('txt,doc,pdf,zip,pdf,mp4,txt,txt,pdf,mp4');
$file->size->range('1024,2048,3072,4096,5120,6144,0,1024,2048,3072');
$file->addedBy->range('admin{10}');
$file->addedDate->range('`2023-05-01 10:00:00`,`2023-05-02 11:00:00`,`2023-05-03 12:00:00`,`2023-05-04 13:00:00`,`2023-05-05 14:00:00`,`2023-05-06 15:00:00`,`2023-05-07 16:00:00`,`2023-05-08 17:00:00`,`2023-05-09 18:00:00`,`2023-05-10 19:00:00`');
$file->deleted->range('0{10}');
$file->gen(10);

$fileTest = new fileTest();

// 测试步骤1：普通文件的路径设置
$normalFile = $fileTest->setFileWebAndRealPathsTest(1);
r(strpos($normalFile->realPath, 'data/upload/1/202305/0414225006610005') !== false) && p() && e('1');

// 测试步骤2：traincourse类型文件的特殊路径设置
$traincourseFile = $fileTest->setFileWebAndRealPathsTest(5);
r(strpos($traincourseFile->realPath, 'data/course/202305/0414225006610009') !== false) && p() && e('1');

// 测试步骤3：traincontents类型文件的特殊路径设置
$traincontentsFile = $fileTest->setFileWebAndRealPathsTest(6);
r(strpos($traincontentsFile->realPath, 'data/course/') !== false) && p() && e('1');

// 测试步骤4：文件name和url属性的自动设置
r(isset($normalFile->name) && $normalFile->name == $normalFile->title) && p() && e('1');

// 测试步骤5：缺失title的文件对象处理（测试ID=7的空title文件）
$emptyTitleFile = $fileTest->setFileWebAndRealPathsTest(7);
r(isset($emptyTitleFile->name)) && p() && e('1');

// 测试步骤6：验证文件下载链接的正确生成
r(strpos($normalFile->url, 'file-download-1') !== false) && p() && e('1');

// 测试步骤7：空pathname的边界情况测试（测试ID=7有空pathname）
r(isset($emptyTitleFile->realPath)) && p() && e('1');