#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getTrashesBySearch();
cid=1
pid=1

搜索objectType all, type all, queryID myQueryID, orderBy id_desc的回收站信息 >> 0
搜索objectType story, type all, queryID myQueryID, orderBy id_desc的回收站信息 >> 48,story
搜索objectType story, type all, queryID 2, orderBy id_asc的回收站信息 >> 48,story
搜索objectType story, type hidden, queryID myQueryID, orderBy id_desc的回收站信息 >> 0
搜索objectType testcase, type all, queryID 0, orderBy id_asc的回收站信息 >> 0

*/

$objectTypeList = array('all', 'story', 'testcase');
$typeList       = array('all', 'hidden');
$queryIdList    = array('myQueryID', '0', '2');
$orderBy        = array('id_desc', 'id_asc');
$pager          = null;

$action = new actionTest();
r($action->getTrashesBySearchTest($objectTypeList[0], $typeList[0], $queryIdList[0], $orderBy[0], $pager)) && p()                   && e('0'); // 搜索objectType all, type all, queryID myQueryID, orderBy id_desc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[1], $typeList[0], $queryIdList[0], $orderBy[0], $pager)) && p('48:id,objectType') && e('48,story'); // 搜索objectType story, type all, queryID myQueryID, orderBy id_desc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[1], $typeList[0], $queryIdList[1], $orderBy[1], $pager)) && p('48:id,objectType') && e('48,story'); // 搜索objectType story, type all, queryID 2, orderBy id_asc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[1], $typeList[1], $queryIdList[0], $orderBy[0], $pager)) && p()                   && e('0'); // 搜索objectType story, type hidden, queryID myQueryID, orderBy id_desc的回收站信息
r($action->getTrashesBySearchTest($objectTypeList[2], $typeList[0], $queryIdList[0], $orderBy[1], $pager)) && p()                   && e('0'); // 搜索objectType testcase, type all, queryID 0, orderBy id_asc的回收站信息
