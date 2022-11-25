#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getDynamicBySearch();
cid=1
pid=1

获取product all, project all, execution all排序为date倒序的所有动态 >> 64,testsuite
获取product all, project all, execution all排序为date正序的所有动态 >> 33,case
获取product 1, project all, execution all排序为date倒序的所有动态 >> 64,testsuite
获取product 1, project all, execution all排序为date正序的所有动态 >> 33,case
获取product all, project 1, execution all排序为date倒序的所有动态 >> 64,testsuite
获取product all, project 1, execution all排序为date正序的所有动态 >> 33,case
获取product all, project all, execution 1排序为date倒序的所有动态 >> 64,testsuite
获取product all, project all, execution 1排序为date正序的所有动态 >> 33,case
获取product all, project all, execution all排序为date倒序的今天之前的动态 >> 33,case
获取product all, project all, execution all排序为date倒序的今天之后的动态 >> 0

*/

$products      = array('all', 1, 2, 3);
$projects      = array('all', 1, 2, 3);
$executions    = array('all', 1, 2, 3);
$queryID       = 0;
$orderByList   = array('date_desc', 'date_asc');
$pager         = null;
$dateList      = array('', 'today');
$directionList = array('next', 'pre');

$action = new actionTest();

r($action->getDynamicBySearchTest($products[0], $projects[0], $executions[0], $queryID, $orderByList[0], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('64,testsuite');  // 获取product all, project all, execution all排序为date倒序的所有动态
r($action->getDynamicBySearchTest($products[0], $projects[0], $executions[0], $queryID, $orderByList[1], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('33,case');       // 获取product all, project all, execution all排序为date正序的所有动态
r($action->getDynamicBySearchTest($products[1], $projects[0], $executions[0], $queryID, $orderByList[0], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('64,testsuite');  // 获取product 1, project all, execution all排序为date倒序的所有动态
r($action->getDynamicBySearchTest($products[1], $projects[0], $executions[0], $queryID, $orderByList[1], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('33,case');       // 获取product 1, project all, execution all排序为date正序的所有动态
r($action->getDynamicBySearchTest($products[0], $projects[1], $executions[0], $queryID, $orderByList[0], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('64,testsuite');  // 获取product all, project 1, execution all排序为date倒序的所有动态
r($action->getDynamicBySearchTest($products[0], $projects[1], $executions[0], $queryID, $orderByList[1], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('33,case');       // 获取product all, project 1, execution all排序为date正序的所有动态
r($action->getDynamicBySearchTest($products[0], $projects[0], $executions[1], $queryID, $orderByList[0], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('64,testsuite');  // 获取product all, project all, execution 1排序为date倒序的所有动态
r($action->getDynamicBySearchTest($products[0], $projects[0], $executions[1], $queryID, $orderByList[1], $pager, $dateList[0], $directionList[0])) && p('1:id,objectType') && e('33,case');       // 获取product all, project all, execution 1排序为date正序的所有动态
r($action->getDynamicBySearchTest($products[0], $projects[0], $executions[0], $queryID, $orderByList[1], $pager, $dateList[1], $directionList[0])) && p('1:id,objectType') && e('33,case');       // 获取product all, project all, execution all排序为date倒序的今天之前的动态
r($action->getDynamicBySearchTest($products[0], $projects[0], $executions[0], $queryID, $orderByList[1], $pager, $dateList[1], $directionList[1])) && p() && e('0');                              // 获取product all, project all, execution all排序为date倒序的今天之后的动态
