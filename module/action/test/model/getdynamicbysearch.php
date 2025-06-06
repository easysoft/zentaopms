#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

/**

title=测试 actionModel->getDynamicBySearch();
timeout=0
cid=1

- 获取排序为date倒序的所有动态
 - 第46条的id属性 @46
 - 第46条的objectType属性 @review
 - 第78条的id属性 @78
 - 第78条的objectType属性 @testcase
- 获取排序为date正序的所有动态
 - 第1条的id属性 @1
 - 第1条的objectType属性 @product
- 获取排序为date倒序的今年之后的动态
 - 第46条的id属性 @46
 - 第46条的objectType属性 @review
- 获取排序为date正序的所有动态
 - 第2条的id属性 @2
 - 第2条的objectType属性 @story
- 获取排序为date倒序的今年之后的动态
 - 第78条的id属性 @78
 - 第78条的objectType属性 @testcase

*/

zenData('action')->loadYaml('action')->gen(90);
zenData('doclib')->gen(1);
zenData('doc')->gen(1);
zenData('lang')->gen(0);
zenData('product')->gen(1);
zenData('userquery')->loadYaml('userquery')->gen(1);

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

r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[0])) && p('46:id,objectType;78:id,objectType') && e('46,review;78,testcase'); // 获取排序为date倒序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $limit, $dateList[0], $directionList[0])) && p('1:id,objectType')                   && e('1,product');             // 获取排序为date正序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[1])) && p('46:id,objectType')                  && e('46,review');             // 获取排序为date倒序的今年之后的动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $limit, $dateList[0], $directionList[0])) && p('2:id,objectType')                   && e('2,story');               // 获取排序为date正序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[1])) && p('78:id,objectType')                  && e('78,testcase');           // 获取排序为date倒序的今年之后的动态
