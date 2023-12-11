#!/usr/bin/env php
<?php

/**

title=测试 fileModel->getByObject();
cid=0

- 获取 traincourse id 5 的文件
 - 第5条的title属性 @文件标题5
 - 第5条的extension属性 @wps
 - 第5条的objectType属性 @traincourse
 - 第5条的objectID属性 @5
 - 第5条的webPath属性 @/data/course/202305/0414225006610009
- 获取 traincontents id 6 的文件
 - 第6条的title属性 @文件标题6
 - 第6条的extension属性 @wri
 - 第6条的objectType属性 @traincontents
 - 第6条的objectID属性 @6
 - 第6条的webPath属性 @/data/course/202305/0414225006610010
- 获取 task id 7 的文件
 - 第7条的title属性 @文件标题7
 - 第7条的extension属性 @pdf
 - 第7条的objectType属性 @task
 - 第7条的objectID属性 @7
 - 第7条的webPath属性 @/data/upload/1/202305/0414225006610011
- 获取 bug id 8 的文件
 - 第8条的title属性 @文件标题8
 - 第8条的extension属性 @ppt
 - 第8条的objectType属性 @bug
 - 第8条的objectID属性 @8
 - 第8条的webPath属性 @/data/upload/1/202305/0414225006610005
- 获取 story id 9 的文件
 - 第9条的title属性 @文件标题9
 - 第9条的extension属性 @pptx
 - 第9条的objectType属性 @story
 - 第9条的objectID属性 @9
 - 第9条的webPath属性 @/data/upload/1/202305/0414225006610006
- 获取 testcase id 10 的文件
 - 第10条的title属性 @文件标题10
 - 第10条的extension属性 @xls
 - 第10条的objectType属性 @testcase
 - 第10条的objectID属性 @10
 - 第10条的webPath属性 @/data/upload/1/202305/0414225006610007
- 获取 testcase id 5 的文件 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = zdTable('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(20);
$objectType = array('traincourse', 'traincontents', 'task', 'bug', 'story', 'testcase');
$objectID   = array(5, 6, 7, 8, 9, 10);
$extra      = 0;

$file = new fileTest();
$file->objectModel->webPath = '/data/upload/1/';
$file->objectModel->config->webRoot = '/';

r($file->getByObjectTest($objectType[0], $objectID[0])) && p('5:title,extension,objectType,objectID,webPath')  && e('文件标题5,wps,traincourse,5,/data/course/202305/0414225006610009');  // 获取 traincourse id 5 的文件
r($file->getByObjectTest($objectType[1], $objectID[1])) && p('6:title,extension,objectType,objectID,webPath')  && e('文件标题6,wri,traincontents,6,/data/course/202305/0414225006610010'); // 获取 traincontents id 6 的文件
r($file->getByObjectTest($objectType[2], $objectID[2])) && p('7:title,extension,objectType,objectID,webPath')  && e('文件标题7,pdf,task,7,/data/upload/1/202305/0414225006610011');       // 获取 task id 7 的文件
r($file->getByObjectTest($objectType[3], $objectID[3])) && p('8:title,extension,objectType,objectID,webPath')  && e('文件标题8,ppt,bug,8,/data/upload/1/202305/0414225006610005');        // 获取 bug id 8 的文件
r($file->getByObjectTest($objectType[4], $objectID[4])) && p('9:title,extension,objectType,objectID,webPath')  && e('文件标题9,pptx,story,9,/data/upload/1/202305/0414225006610006');    // 获取 story id 9 的文件
r($file->getByObjectTest($objectType[5], $objectID[5])) && p('10:title,extension,objectType,objectID,webPath') && e('文件标题10,xls,testcase,10,/data/upload/1/202305/0414225006610007'); // 获取 testcase id 10 的文件
r($file->getByObjectTest($objectType[5], $objectID[0])) && p()                                                 && e('0');                                                                     // 获取 testcase id 5 的文件
