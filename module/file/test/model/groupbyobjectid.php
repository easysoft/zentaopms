#!/usr/bin/env php
<?php

/**

title=测试 fileModel->groupByObjectID();
timeout=0
cid=16515

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
su('admin');

$file = zenData('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(20);
$objectType = array('traincourse', 'traincontents', 'task', 'bug', 'story', 'testcase');
$objectID   = array(5, 6, 7, 8, 9, 10);
$extra      = 0;

global $tester;
$tester->loadModel('file');
$tester->file->webPath = '/data/upload/1/';
$tester->file->config->webRoot = '/';

r($tester->file->groupByObjectID('traincourse',    '5')[5])   && p('5:title,extension,objectType,objectID,webPath')  && e('文件标题5,wps,traincourse,5,/data/course/202305/0414225006610009');   // 获取 traincourse id 5 的文件
r($tester->file->groupByObjectID('traincontents',  '6')[6])   && p('6:title,extension,objectType,objectID,webPath')  && e('文件标题6,wri,traincontents,6,/data/course/202305/0414225006610010'); // 获取 traincontents id 6 的文件
r($tester->file->groupByObjectID('task',           '7')[7])   && p('7:title,extension,objectType,objectID,webPath')  && e('文件标题7,pdf,task,7,/data/upload/1/202305/0414225006610011');        // 获取 task id 7 的文件
r($tester->file->groupByObjectID('bug',            '8')[8])   && p('8:title,extension,objectType,objectID,webPath')  && e('文件标题8,ppt,bug,8,/data/upload/1/202305/0414225006610005');         // 获取 bug id 8 的文件
r($tester->file->groupByObjectID('story',          '9')[9])   && p('9:title,extension,objectType,objectID,webPath')  && e('文件标题9,pptx,story,9,/data/upload/1/202305/0414225006610006');      // 获取 story id 9 的文件
r($tester->file->groupByObjectID('testcase',       '10')[10]) && p('10:title,extension,objectType,objectID,webPath') && e('文件标题10,xls,testcase,10,/data/upload/1/202305/0414225006610007');  // 获取 testcase id 10 的文件
r($tester->file->groupByObjectID('testcase',       5))        && p()                                                 && e('0');                                                                  // 获取 testcase id 5 的文件
