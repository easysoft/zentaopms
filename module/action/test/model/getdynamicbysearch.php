#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(90);
zdTable('doclib')->config('doclib')->gen(15);
zdTable('doc')->config('doc')->gen(5);
zdTable('product')->gen(15);
zdTable('userquery')->config('userquery')->gen(1);

/**

title=测试 actionModel->getDynamicBySearch();
cid=1
pid=1

获取排序为date倒序的所有动态                   >> 32,testcase
获取排序为date倒序的所有动态                   >> 1,product
获取排序为date正序的今天之前的动态             >> 32,testcase
获取queryID为1，排序为date倒序的今天之后的动态 >> 1,product

*/

$queryID       = array(0, 1);
$orderByList   = array('date_desc', 'date_asc');
$pager         = null;
$dateList      = array('', 'today');
$directionList = array('next', 'pre');

$action = new actionTest();

r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('32,testcase');  // 获取product all, project all, execution all排序为date倒序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[1], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('1,product');    // 获取product all, project all, execution all排序为date正序的所有动态
r($action->getDynamicBySearchTest($queryID[0], $orderByList[0], $pager, $dateList[0], $directionList[1])) && p('1:id,objectType') && e('32,testcase');  // 获取product all, project all, execution all排序为date倒序的今天之后的动态
r($action->getDynamicBySearchTest($queryID[1], $orderByList[0], $pager, $dateList[0], $directionList[1])) && p('0:id,objectType') && e('1,product');    // 获取product all, project all, execution all排序为date倒序的今天之后的动态
