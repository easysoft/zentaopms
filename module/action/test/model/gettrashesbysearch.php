#!/usr/bin/env php
<?php

use function zin\wg;

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('action')->loadYaml('action')->gen(20);
zenData('actionrecent')->gen(0);
zenData('userquery')->loadYaml('userquery')->gen(2);
zenData('story')->gen(10);
$providerTable = zenData('ops_provider');
$providerTable->id->range('1-10');
$providerTable->type->range('gitlab{10}');
$providerTable->name->range('server1,server2,server3,server4,server5,server6,server7,server8,server9,server10');
$providerTable->deleted->range('0{10}');
$providerTable->gen(10);

/**

title=测试 actionModel->getTrashesBySearch();
timeout=0
cid=14912

- 搜索objectType all,      type all,    queryID myQueryID, orderBy id_desc的回收站信息 @0
- 搜索objectType story,    type all,    queryID myQueryID, orderBy id_desc的回收站信息
 - 第1条的id属性 @11
 - 第1条的objectID属性 @1
 - 第1条的objectName属性 @用户需求1
 - 第9条的id属性 @9
 - 第9条的objectID属性 @9
 - 第9条的objectName属性 @用户需求9
- 搜索objectType all,      type hidden, queryID myQueryID, orderBy id_desc的回收站信息
 - 第2条的id属性 @2
 - 第2条的objectID属性 @2
 - 第2条的objectName属性 @软件需求2
 - 第10条的id属性 @10
 - 第10条的objectID属性 @10
 - 第10条的objectName属性 @软件需求10
- 搜索objectType all,      type all,    queryID 1,         orderBy id_desc的回收站信息
 - 第1条的id属性 @1
 - 第1条的objectID属性 @1
 - 第1条的objectName属性 @用户需求1
- 搜索objectType provider, type all,    queryID myQueryID, orderBy id_desc的回收站信息
 - 第1条的id属性 @1
 - 第1条的objectType属性 @provider
 - 第1条的objectID属性 @1

*/

$objectTypeList = array('all', 'story', 'provider');
$typeList       = array('all', 'hidden');
$queryIdList    = array('myQueryID', 1);
$orderBy        = array('id_desc', 'id_asc');
$pager          = null;

$action = new actionModelTest();

r($action->getTrashesBySearchTest($objectTypeList[0], $typeList[0], $queryIdList[0], $orderBy[0], $pager)) && p()                                                     && e('0');                              // 搜索objectType all,      type all,    queryID myQueryID, orderBy id_desc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[1], $typeList[0], $queryIdList[0], $orderBy[0], $pager)) && p('1:id,objectID,objectName;9:id,objectID,objectName')  && e('1,1,用户需求1;9,9,用户需求9');    // 搜索objectType story,    type all,    queryID myQueryID, orderBy id_desc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[1], $typeList[1], $queryIdList[0], $orderBy[0], $pager)) && p('2:id,objectID,objectName;10:id,objectID,objectName') && e('2,2,软件需求2;10,10,软件需求10'); // 搜索objectType all,      type hidden, queryID myQueryID, orderBy id_desc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[1], $typeList[0], $queryIdList[1], $orderBy[0], $pager)) && p('1:id,objectID,objectName')                           && e('1,1,用户需求1');                  // 搜索objectType all,      type all,    queryID 1,         orderBy id_desc的回收站信息
$tester->session->set('trashQuery', false);
r($action->getTrashesBySearchTest($objectTypeList[2], $typeList[0], $queryIdList[0], $orderBy[0], $pager)) && p('1:id,objectType,objectID')                           && e('11,provider,1');                  // 搜索objectType provider, type all,    queryID myQueryID, orderBy id_desc的回收站信息
