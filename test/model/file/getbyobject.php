#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getByObject();
cid=1
pid=1

获取 traincourse id 5 的文件 >> 这是一个文件名称5.wps,wps,traincourse,5,data/course/202005/0414225006610005.wps
获取 traincontents id 6 的文件 >> 这是一个文件名称6.wri,wri,traincontents,6,data/course/202006/0414225006610006.wri
获取 task id 7 的文件 >> 这是一个文件名称7.pdf,pdf,task,7,/data/upload/1/202007/0414225006610007
获取 bug id 8 的文件 >> 这是一个文件名称8.ppt,ppt,bug,8,/data/upload/1/202008/0414225006610008
获取 story id 9 的文件 >> 这是一个文件名称9.pptx,pptx,story,9,/data/upload/1/202009/0414225006610009
获取 testcase id 10 的文件 >> 这是一个文件名称10.xls,xls,testcase,10,/data/upload/1/202001/0414225006610010
获取 testcase id 5 的文件 >> 0

*/
$objectType = array('traincourse', 'traincontents', 'task', 'bug', 'story', 'testcase');
$objectID   = array(5, 6, 7, 8, 9, 10);
$extra      = 0;

$file = new fileTest();

r($file->getByObjectTest($objectType[0], $objectID[0])) && p('5:title,extension,objectType,objectID,webPath')  && e('这是一个文件名称5.wps,wps,traincourse,5,data/course/202005/0414225006610005.wps');   // 获取 traincourse id 5 的文件
r($file->getByObjectTest($objectType[1], $objectID[1])) && p('6:title,extension,objectType,objectID,webPath')  && e('这是一个文件名称6.wri,wri,traincontents,6,data/course/202006/0414225006610006.wri'); // 获取 traincontents id 6 的文件
r($file->getByObjectTest($objectType[2], $objectID[2])) && p('7:title,extension,objectType,objectID,webPath')  && e('这是一个文件名称7.pdf,pdf,task,7,/data/upload/1/202007/0414225006610007');          // 获取 task id 7 的文件
r($file->getByObjectTest($objectType[3], $objectID[3])) && p('8:title,extension,objectType,objectID,webPath')  && e('这是一个文件名称8.ppt,ppt,bug,8,/data/upload/1/202008/0414225006610008');           // 获取 bug id 8 的文件
r($file->getByObjectTest($objectType[4], $objectID[4])) && p('9:title,extension,objectType,objectID,webPath')  && e('这是一个文件名称9.pptx,pptx,story,9,/data/upload/1/202009/0414225006610009');       // 获取 story id 9 的文件
r($file->getByObjectTest($objectType[5], $objectID[5])) && p('10:title,extension,objectType,objectID,webPath') && e('这是一个文件名称10.xls,xls,testcase,10,/data/upload/1/202001/0414225006610010');    // 获取 testcase id 10 的文件
r($file->getByObjectTest($objectType[5], $objectID[0])) && p()                                                 && e('0');                                                                                 // 获取 testcase id 5 的文件