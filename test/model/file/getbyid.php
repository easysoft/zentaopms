#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getById();
cid=1
pid=1

测试查询 fileID 1 的信息 >> 这是一个文件名称1.txt,txt,task,1,/data/upload/1/202001/0414225006610001
测试查询 fileID 2 的信息 >> 这是一个文件名称2.doc,doc,bug,2,/data/upload/1/202002/0414225006610002
测试查询 fileID 11 的信息 >> 这是一个文件名称11.xlsx,xlsx,traincourse,11,data/course/202002/0414225006610011.xlsx
测试查询 fileID 24 的信息 >> 这是一个文件名称24.avi,avi,traincontents,24,data/course/202006/0414225006610024.avi
测试查询 fileID 81 的信息 >> 这是一个文件名称81.tar,tar,story,81,/data/upload/1/202009/0414225006610081
测试查询 fileID 101 的信息 >> 0

*/

$fileID = array(1, 2, 11, 24, 81, 101);

$file = new fileTest();

r($file->getByIdTest($fileID[0])) && p('title,extension,objectType,objectID,webPath') && e('这是一个文件名称1.txt,txt,task,1,/data/upload/1/202001/0414225006610001');              // 测试查询 fileID 1 的信息
r($file->getByIdTest($fileID[1])) && p('title,extension,objectType,objectID,webPath') && e('这是一个文件名称2.doc,doc,bug,2,/data/upload/1/202002/0414225006610002');               // 测试查询 fileID 2 的信息
r($file->getByIdTest($fileID[2])) && p('title,extension,objectType,objectID,webPath') && e('这是一个文件名称11.xlsx,xlsx,traincourse,11,data/course/202002/0414225006610011.xlsx'); // 测试查询 fileID 11 的信息
r($file->getByIdTest($fileID[3])) && p('title,extension,objectType,objectID,webPath') && e('这是一个文件名称24.avi,avi,traincontents,24,data/course/202006/0414225006610024.avi');  // 测试查询 fileID 24 的信息
r($file->getByIdTest($fileID[4])) && p('title,extension,objectType,objectID,webPath') && e('这是一个文件名称81.tar,tar,story,81,/data/upload/1/202009/0414225006610081');           // 测试查询 fileID 81 的信息
r($file->getByIdTest($fileID[5])) && p('title,extension,objectType,objectID,webPath') && e('0');                                                                                    // 测试查询 fileID 101 的信息