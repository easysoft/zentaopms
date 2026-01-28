#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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
cid=14898

- 获取排序为id倒序的所有动态
 - 第0条的id属性 @90
 - 第0条的objectType属性 @entry
 - 第1条的id属性 @89
 - 第1条的objectType属性 @testreport
- 获取排序为id正序的所有动态
 - 第0条的id属性 @1
 - 第0条的objectType属性 @product
 - 第1条的id属性 @2
 - 第1条的objectType属性 @story
- 获取排序为id倒序的今年之后的动态
 - 第0条的id属性 @90
 - 第0条的objectType属性 @entry
 - 第1条的id属性 @89
 - 第1条的objectType属性 @testreport
- 获取排序为id倒序的今天之前的动态
 - 第0条的id属性 @90
 - 第0条的objectType属性 @entry
 - 第1条的id属性 @89
 - 第1条的objectType属性 @testreport
- 获取排序为id倒序的今年之后的动态
 - 第0条的id属性 @86
 - 第0条的objectType属性 @module

*/

global $lang, $app;
$lang->SRCommon = '研发需求';
$lang->URCommon = '用户需求';
$app->loadLang('action');

su('admin');

$queryID       = array(0, 1);
$orderByList   = array('id_desc', 'id_asc');
$limit         = 50;
$dateList      = array('', 'today');
$directionList = array('next', 'pre');

$action = new actionModelTest();

r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[0]))) && p('0:id,objectType;1:id,objectType') && e('90,entry;89,testreport'); // 获取排序为id倒序的所有动态
r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $limit, $dateList[0], $directionList[0]))) && p('0:id,objectType;1:id,objectType') && e('1,product;2,story');      // 获取排序为id正序的所有动态
r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[1]))) && p('0:id,objectType;1:id,objectType') && e('90,entry;89,testreport'); // 获取排序为id倒序的今年之后的动态
r(array_values($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[1], $directionList[0]))) && p('0:id,objectType;1:id,objectType') && e('90,entry;89,testreport'); // 获取排序为id倒序的今天之前的动态
r(array_values($action->getDynamicBySearchTest($queryID[1], $orderByList[0], $limit, $dateList[0], $directionList[1]))) && p('0:id,objectType')                 && e('86,module');              // 获取排序为id倒序的今年之后的动态
