#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

/**

title=测试 actionModel->getDynamicBySearch();
timeout=0
cid=1

- 获取排序为date倒序的所有动态
 - 第0条的id属性 @90
 - 第0条的objectType属性 @entry
 - 第1条的id属性 @60
 - 第1条的objectType属性 @doclib
- 获取排序为date正序的所有动态
 - 第0条的id属性 @61
 - 第0条的objectType属性 @todo
 - 第1条的id属性 @31
 - 第1条的objectType属性 @bug
- 获取排序为date倒序的今年之后的动态
 - 第0条的id属性 @90
 - 第0条的objectType属性 @entry
 - 第1条的id属性 @60
 - 第1条的objectType属性 @doclib
- 获取排序为date倒序的今天之前的动态
 - 第0条的id属性 @89
 - 第0条的objectType属性 @testreport
 - 第1条的id属性 @59
 - 第1条的objectType属性 @doc
- 获取排序为date倒序的今年之后的动态
 - 第0条的id属性 @1
 - 第0条的objectType属性 @product

*/

zdTable('action')->config('action')->gen(90);
zdTable('doclib')->gen(1);
zdTable('doc')->gen(1);
zdTable('product')->gen(1);
zdTable('userquery')->config('userquery')->gen(1);

su('admin');

$queryID       = array(0, 1);
$orderByList   = array('date_desc,id_desc', 'date_asc,id_desc');
$limit         = 50;
$dateList      = array('', 'today');
$directionList = array('next', 'pre');

$action = new actionTest();

r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[0])) && p('0:id,objectType;1:id,objectType') && e('90,entry;60,doclib');   // 获取排序为date倒序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $limit, $dateList[0], $directionList[0])) && p('0:id,objectType;1:id,objectType') && e('61,todo;31,bug');       // 获取排序为date正序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[0], $directionList[1])) && p('0:id,objectType;1:id,objectType') && e('90,entry;60,doclib');   // 获取排序为date倒序的今年之后的动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $limit, $dateList[1], $directionList[0])) && p('0:id,objectType;1:id,objectType') && e('89,testreport;59,doc'); // 获取排序为date倒序的今天之前的动态
r($action->getDynamicBySearchTest($queryID[1], $orderByList[0], $limit, $dateList[0], $directionList[1])) && p('0:id,objectType')                 && e('1,product');            // 获取排序为date倒序的今年之后的动态
