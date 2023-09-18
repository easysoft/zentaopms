#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(20);
zdTable('testreport')->config('testreport')->gen(1);
zdTable('doc')->gen(1);

/**

title=测试 actionModel->getTrashes();
cid=1
pid=1

查询objectType all type all id desc排序的回收站信息   >> testreport,1;caselib,1
查询objectType all type all id desc排序的回收站信息   >> product,1,正常产品1;story,1,首页设计和开发
查询objectType all type hidden id asc排序的回收站信息 >> doc,1,文档标题1
查询objectType all type hidden id asc排序的回收站信息 >> testcase,1,售后服务的测试用例
查询objectType story type all id asc排序的回收站信息  >> story,1,首页设计和开发
查询objectType testcase hidden id asc排序的回收站信息 >> testcase,1,售后服务的测试用例


*/

$objectTypeList = array('all', 'story', 'testcase');
$typeList       = array('all', 'hidden');
$orderBy        = array('id_desc', 'id_asc');
$pager          = null;

$action = new actionTest();

r($action->getTrashesTest($objectTypeList[0], $typeList[0], $orderBy[0], $pager)) && p('0:objectType,objectID;1:objectType,objectID')                       && e('testreport,1;caselib,1');                     // 查询objectType all type all id desc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[0], $orderBy[1], $pager)) && p('0:objectType,objectID,objectName;1:objectType,objectID,objectName') && e('product,1,正常产品1;story,1,首页设计和开发'); // 查询objectType all type all id desc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[1], $orderBy[0], $pager)) && p('0:objectType,objectID,objectName')                                  && e('doc,1,文档标题1');                            // 查询objectType all type hidden id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[1], $orderBy[1], $pager)) && p('0:objectType,objectID,objectName')                                  && e('testcase,1,售后服务的测试用例');              // 查询objectType all type hidden id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[1], $typeList[0], $orderBy[0], $pager)) && p('0:objectType,objectID,objectName')                                  && e('story,1,首页设计和开发');                     // 查询objectType story type all id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[2], $typeList[1], $orderBy[0], $pager)) && p('0:objectType,objectID,objectName')                                  && e('testcase,1,售后服务的测试用例');              // 查询objectType testcase hidden id asc排序的回收站信息
