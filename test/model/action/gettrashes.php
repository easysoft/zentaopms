#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getTrashes();
cid=1
pid=1

查询objectType all type all id desc排序的回收站信息 >> testsuite,,这是测试套件名称87;story,,软件需求48
查询objectType all type all id desc排序的回收站信息 >> story,,软件需求48;testsuite,,这是测试套件名称87
查询objectType all type hidden id asc排序的回收站信息 >> testcase,,这个是测试用例9
查询objectType all type hidden id asc排序的回收站信息 >> testcase,,这个是测试用例9
查询objectType story type all id asc排序的回收站信息 >> story,,软件需求48
查询objectType testcase hidden id asc排序的回收站信息 >> testcase,,这个是测试用例9

*/

$objectTypeList = array('all', 'story', 'testcase');
$typeList       = array('all', 'hidden');
$orderBy        = array('id_desc', 'id_asc');
$pager          = null;

$action = new actionTest();

r($action->getTrashesTest($objectTypeList[0], $typeList[0], $orderBy[0], $pager)) && p('0:objectType,objectId,objectName;1:objectType,objectId,objectName') && e('testsuite,,这是测试套件名称87;story,,软件需求48'); // 查询objectType all type all id desc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[0], $orderBy[1], $pager)) && p('0:objectType,objectId,objectName;1:objectType,objectId,objectName') && e('story,,软件需求48;testsuite,,这是测试套件名称87'); // 查询objectType all type all id desc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[1], $orderBy[0], $pager)) && p('0:objectType,objectId,objectName')                                  && e('testcase,,这个是测试用例9');                       // 查询objectType all type hidden id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[1], $orderBy[1], $pager)) && p('0:objectType,objectId,objectName')                                  && e('testcase,,这个是测试用例9');                       // 查询objectType all type hidden id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[1], $typeList[0], $orderBy[0], $pager)) && p('0:objectType,objectId,objectName')                                  && e('story,,软件需求48');                       // 查询objectType story type all id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[2], $typeList[1], $orderBy[0], $pager)) && p('0:objectType,objectId,objectName')                                  && e('testcase,,这个是测试用例9');                       // 查询objectType testcase hidden id asc排序的回收站信息