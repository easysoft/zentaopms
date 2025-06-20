#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->loadYaml('action')->gen(90);
zenData('actionrecent')->gen(0);
zenData('actionproduct')->loadYaml('actionproduct')->gen(90);
zenData('doclib')->gen(1);
zenData('doc')->gen(1);
zenData('lang')->gen(0);
zenData('product')->gen(1);
zenData('userquery')->loadYaml('userquery')->gen(1);

/**

title=测试 actionModel->getDynamicBySearch();
timeout=0
cid=1

- 获取排序为date倒序的所有动态
 - 第0条的id属性 @64
 - 第0条的objectType属性 @testsuite
 - 第1条的id属性 @32
 - 第1条的objectType属性 @testcase
- 获取排序为date正序的所有动态
 - 第0条的id属性 @65
 - 第0条的objectType属性 @caselib
 - 第1条的id属性 @33
 - 第1条的objectType属性 @case
- 获取排序为date倒序的今年之后的动态
 - 第0条的id属性 @64
 - 第0条的objectType属性 @testsuite
 - 第1条的id属性 @32
 - 第1条的objectType属性 @testcase
- 获取排序为date倒序的今天之前的动态
 - 第0条的id属性 @63
 - 第0条的objectType属性 @module
 - 第1条的id属性 @31
 - 第1条的objectType属性 @bug
- 获取排序为date倒序的今年之后的动态
 - 第0条的id属性 @31
 - 第0条的objectType属性 @bug

*/

global $lang, $app;
$lang->SRCommon = '研发需求';
$lang->URCommon = '用户需求';
$app->loadLang('action');

su('admin');

$queryID       = array(0, 1);
$orderByList   = array('date_desc,id_desc', 'date_asc,id_desc');
$limit         = 50;
$dateList      = array('', 'today');
$directionList = array('next', 'pre');

$action = new actionTest();

r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[0]))) && p('0:id,objectType;1:id,objectType') && e('64,testsuite;32,testcase'); // 获取排序为date倒序的所有动态
r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $limit, $dateList[0], $directionList[0]))) && p('0:id,objectType;1:id,objectType') && e('65,caselib;33,case');       // 获取排序为date正序的所有动态
r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[1]))) && p('0:id,objectType;1:id,objectType') && e('64,testsuite;32,testcase'); // 获取排序为date倒序的今年之后的动态
r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[1], $directionList[0]))) && p('0:id,objectType;1:id,objectType') && e('63,module;31,bug');         // 获取排序为date倒序的今天之前的动态
r(array_values($action->getDynamicBySearchTest($queryID[1], $orderByList[0], $limit, $dateList[0], $directionList[1]))) && p('0:id,objectType')                 && e('31,bug');                   // 获取排序为date倒序的今年之后的动态
