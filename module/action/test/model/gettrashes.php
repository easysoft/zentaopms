#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(20);
zdTable('testreport')->config('testreport')->gen(1);
zdTable('doc')->gen(1);
zdTable('story')->gen(1);
zdTable('product')->gen(1);

/**

title=测试 actionModel->getTrashes();
timeout=0
cid=1

- 查询objectType all type all id desc排序的回收站信息
 - 第0条的objectType属性 @testreport
 - 第0条的objectID属性 @1
 - 第1条的objectType属性 @caselib
 - 第1条的objectID属性 @1
- 查询objectType all type all id desc排序的回收站信息
 - 第0条的objectType属性 @product
 - 第0条的objectID属性 @1
 - 第0条的objectName属性 @正常产品1
 - 第1条的objectType属性 @story
 - 第1条的objectID属性 @1
 - 第1条的objectName属性 @用户需求1
- 查询objectType all type hidden id asc排序的回收站信息
 - 第0条的objectType属性 @doc
 - 第0条的objectID属性 @1
 - 第0条的objectName属性 @文档标题1
- 查询objectType all type hidden id asc排序的回收站信息
 - 第0条的objectType属性 @testcase
 - 第0条的objectID属性 @1
 - 第0条的objectName属性 @这个是测试用例1
- 查询objectType story type all id asc排序的回收站信息
 - 第0条的objectType属性 @story
 - 第0条的objectID属性 @1
 - 第0条的objectName属性 @用户需求1
- 查询objectType testcase hidden id asc排序的回收站信息
 - 第0条的objectType属性 @testcase
 - 第0条的objectID属性 @1
 - 第0条的objectName属性 @这个是测试用例1

*/

$objectTypeList = array('all', 'story', 'testcase');
$typeList       = array('all', 'hidden');
$orderBy        = array('id_desc', 'id_asc');
$pager          = null;

$action = new actionTest();

r($action->getTrashesTest($objectTypeList[0], $typeList[0], $orderBy[0], $pager)) && p('0:objectType,objectID;1:objectType,objectID')                       && e('testreport,1;caselib,1');                     // 查询objectType all type all id desc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[0], $orderBy[1], $pager)) && p('0:objectType,objectID,objectName;1:objectType,objectID,objectName') && e('product,1,正常产品1;story,1,用户需求1');      // 查询objectType all type all id desc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[1], $orderBy[0], $pager)) && p('0:objectType,objectID,objectName')                                  && e('doc,1,文档标题1');                            // 查询objectType all type hidden id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[0], $typeList[1], $orderBy[1], $pager)) && p('0:objectType,objectID,objectName')                                  && e('testcase,1,这个是测试用例1');                 // 查询objectType all type hidden id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[1], $typeList[0], $orderBy[0], $pager)) && p('0:objectType,objectID,objectName')                                  && e('story,1,用户需求1');                          // 查询objectType story type all id asc排序的回收站信息
r($action->getTrashesTest($objectTypeList[2], $typeList[1], $orderBy[0], $pager)) && p('0:objectType,objectID,objectName')                                  && e('testcase,1,这个是测试用例1');                 // 查询objectType testcase hidden id asc排序的回收站信息