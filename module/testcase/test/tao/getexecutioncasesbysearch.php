#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

zenData('projectcase')->gen(10);
zenData('case')->gen(10);
$userquery = zenData('userquery');
$userquery->sql->range("(( 1   AND `title`  LIKE '%2%' ) AND ( 1  )) AND deleted = '0'");
$userquery->gen(10);

/**

title=测试 testcaseModel->getExecutionCasesBySearch();
timeout=0
cid=19038

- 测试获取执行 101 产品 0 分支 all paramID 0 query 查所有 id 倒序排序的用例 @4;3;2;1
- 测试获取执行 101 产品 0 分支 all paramID 0 query 查所有 id 正序排序的用例 @1;2;3;4
- 测试获取执行 101 产品 0 分支 all paramID 0 query 标题内含2 id 倒序排序的用例 @1
- 测试获取执行 101 产品 0 分支 all paramID 1 query 查所有 id 倒序排序的用例 @2
- 测试获取执行 101 产品 0 分支 0 paramID 0 query 查所有 id 倒序排序的用例 @4;3;2;1
- 测试获取执行 101 产品 1 分支 all paramID 0 query 查所有 id 倒序排序的用例 @4;3;2;1
- 测试获取执行 102 产品 0 分支 all paramID 0 query 查所有 id 倒序排序的用例 @8;7;6;5
- 测试获取执行 102 产品 1 分支 1 paramID 1 query 标题内含 2 id 正序排序的用例 @0

*/

$executionID = array(101, 102, 103, 104, 105);
$productID   = array(0, 1);
$branchID    = array('all', 0);
$paramID     = array(0, 1);
$query       = array("`product` = 'all' and `branch` = 'all'", "`title` like '%1%'");
$orderBy     = array('id_desc', 'id_asc');

$testcase = new testcaseTest();

r($testcase->getExecutionCasesBySearchTest($executionID[0], $productID[0], $branchID[0], $paramID[0], $query[0], $orderBy[0])) && p() && e('4;3;2;1'); // 测试获取执行 101 产品 0 分支 all paramID 0 query 查所有 id 倒序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[0], $productID[0], $branchID[0], $paramID[0], $query[0], $orderBy[1])) && p() && e('1;2;3;4'); // 测试获取执行 101 产品 0 分支 all paramID 0 query 查所有 id 正序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[0], $productID[0], $branchID[0], $paramID[0], $query[1], $orderBy[0])) && p() && e('1');       // 测试获取执行 101 产品 0 分支 all paramID 0 query 标题内含2 id 倒序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[0], $productID[0], $branchID[0], $paramID[1], $query[0], $orderBy[0])) && p() && e('2');       // 测试获取执行 101 产品 0 分支 all paramID 1 query 查所有 id 倒序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[0], $productID[0], $branchID[1], $paramID[0], $query[0], $orderBy[0])) && p() && e('4;3;2;1'); // 测试获取执行 101 产品 0 分支 0 paramID 0 query 查所有 id 倒序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[0], $productID[1], $branchID[0], $paramID[0], $query[0], $orderBy[0])) && p() && e('4;3;2;1'); // 测试获取执行 101 产品 1 分支 all paramID 0 query 查所有 id 倒序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[1], $productID[0], $branchID[0], $paramID[0], $query[0], $orderBy[0])) && p() && e('8;7;6;5'); // 测试获取执行 102 产品 0 分支 all paramID 0 query 查所有 id 倒序排序的用例
r($testcase->getExecutionCasesBySearchTest($executionID[1], $productID[1], $branchID[1], $paramID[1], $query[1], $orderBy[1])) && p() && e('0');       // 测试获取执行 102 产品 1 分支 1 paramID 1 query 标题内含 2 id 正序排序的用例
