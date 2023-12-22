#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(90);
zdTable('doclib')->gen(1);
zdTable('doc')->gen(1);
zdTable('product')->gen(1);
zdTable('userquery')->config('userquery')->gen(1);

su('admin');

/**

title=测试 actionModel->getDynamicBySearch();
cid=1
pid=1

获取排序为date倒序的所有动态        >> 62,branch;31,bug
获取排序为date正序的所有动态        >> 1,product;32,testcase
获取排序为date倒序的今年之后的动态  >> 62,branch;31,bug
获取排序为date倒序的今天之前的动态  >> 61,todo;30,build
获取排序为date倒序的今年之后的动态  >> 1,product

*/

$queryID       = array(0, 1);
$orderByList   = array('date_desc,id_desc', 'date_asc,id_desc');
$limit         = 50;
$dateList      = array('', 'today');
$directionList = array('next', 'pre');

$action = new actionTest();

r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[0])) && p('0:id,objectType;1:id,objectType') && e('62,branch;31,bug');       // 获取排序为date倒序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $limit, $dateList[0], $directionList[0])) && p('0:id,objectType;1:id,objectType') && e('63,module;32,testcase');  // 获取排序为date正序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[1])) && p('0:id,objectType;1:id,objectType') && e('62,branch;31,bug');       // 获取排序为date倒序的今年之后的动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[1], $directionList[0])) && p('0:id,objectType;1:id,objectType') && e('61,todo;30,build');       // 获取排序为date倒序的今天之前的动态
r($action->getDynamicBySearchTest($queryID[1], $orderByList[0], $limit, $dateList[0], $directionList[1])) && p('0:id,objectType')                 && e('1,product');              // 获取排序为date倒序的今年之后的动态
