#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getById();
cid=0

- 测试查询 fileID 5 的信息
 - 属性title @文件标题5
 - 属性extension @wps
 - 属性objectType @traincourse
 - 属性objectID @5
 - 属性webPath @/data/course/202305/0414225006610009
- 测试查询 fileID 6 的信息
 - 属性title @文件标题6
 - 属性extension @wri
 - 属性objectType @traincontents
 - 属性objectID @6
 - 属性webPath @/data/course/202305/0414225006610010
- 测试查询 fileID 7 的信息
 - 属性title @文件标题7
 - 属性extension @pdf
 - 属性objectType @task
 - 属性objectID @7
 - 属性webPath @/data/upload/1/202305/0414225006610011
- 测试查询 fileID 8 的信息
 - 属性title @文件标题8
 - 属性extension @ppt
 - 属性objectType @bug
 - 属性objectID @8
 - 属性webPath @/data/upload/1/202305/0414225006610005
- 测试查询 fileID 9 的信息
 - 属性title @文件标题9
 - 属性extension @pptx
 - 属性objectType @story
 - 属性objectID @9
 - 属性webPath @/data/upload/1/202305/0414225006610006
- 测试查询 fileID 101 的信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = zdTable('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(20);

$fileID = array(5, 6, 7, 8, 9, 101);

$file = new fileTest();
$file->objectModel->webPath = '/data/upload/1/';
$file->objectModel->config->webRoot = '/';

r($file->getByIdTest($fileID[0])) && p('title,extension,objectType,objectID,webPath') && e('文件标题5,wps,traincourse,5,/data/course/202305/0414225006610009');   // 测试查询 fileID 5 的信息
r($file->getByIdTest($fileID[1])) && p('title,extension,objectType,objectID,webPath') && e('文件标题6,wri,traincontents,6,/data/course/202305/0414225006610010'); // 测试查询 fileID 6 的信息
r($file->getByIdTest($fileID[2])) && p('title,extension,objectType,objectID,webPath') && e('文件标题7,pdf,task,7,/data/upload/1/202305/0414225006610011');        // 测试查询 fileID 7 的信息
r($file->getByIdTest($fileID[3])) && p('title,extension,objectType,objectID,webPath') && e('文件标题8,ppt,bug,8,/data/upload/1/202305/0414225006610005');         // 测试查询 fileID 8 的信息
r($file->getByIdTest($fileID[4])) && p('title,extension,objectType,objectID,webPath') && e('文件标题9,pptx,story,9,/data/upload/1/202305/0414225006610006');      // 测试查询 fileID 9 的信息
r($file->getByIdTest($fileID[5])) && p()                                              && e('0');                                                                  // 测试查询 fileID 101 的信息
