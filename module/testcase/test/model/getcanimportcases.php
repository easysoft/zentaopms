#!/usr/bin/env php
<?php

/**

title=测试 testsuiteModel->getCanImportCases();
cid=18982

- 测试获取产品 1 用例库 0 分支 all 排序 id_desc 可以导入的用例 @29,27,25,23,21

- 测试获取产品 1 用例库 1 分支 all 排序 id_desc 可以导入的用例 @30,28,26,24,22

- 测试获取产品 1 用例库 0 分支 all 排序 id_asc 可以导入的用例 @21,23,25,27,29

- 测试获取产品 41 用例库 0 分支 all 排序 id_desc 可以导入的用例 @29,27,25,23,21

- 测试获取产品 41 用例库 1 分支 all 排序 id_desc 可以导入的用例 @30,28,26,24,22

- 测试获取产品 41 用例库 0 分支 all 排序 id_asc 可以导入的用例 @21,23,25,27,29

- 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery false 可以导入的用例 @29,27,25,23,21

- 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery false 可以导入的用例 @0
- 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery "`lib` = 'all'" 可以导入的用例 @30,29,28,27,26,25,24,23,22,21

- 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery "`lib` = 'all'" 可以导入的用例 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(45);
zenData('branch')->gen(20);
zenData('case')->loadYaml('case')->gen(50);
zenData('module')->loadYaml('module_import')->gen(50);
zenData('userquery')->loadYaml('userquery')->gen(1);
zenData('user')->gen(1);

su('admin');

$productIdList  = array(1, 41);
$libIdList      = array(0, 1);
$orderByList    = array('id_desc', 'id_asc');
$browseTypeList = array('', 'bysearch');
$queryIdList    = array(0, 1);
$testsuiteQuery = array(false, "`lib` = 'all'");

$tester->loadModel('testcase');

r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $orderByList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[1], $orderByList[0])))) && p() && e('30,28,26,24,22'); // 测试获取产品 1 用例库 1 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $orderByList[1])))) && p() && e('21,23,25,27,29'); // 测试获取产品 1 用例库 0 分支 all 排序 id_asc 可以导入的用例

r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[0], $orderByList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 41 用例库 0 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[1], $orderByList[0])))) && p() && e('30,28,26,24,22'); // 测试获取产品 41 用例库 1 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[0], $orderByList[1])))) && p() && e('21,23,25,27,29'); // 测试获取产品 41 用例库 0 分支 all 排序 id_asc 可以导入的用例

$tester->session->set('testsuiteQuery', $testsuiteQuery[0]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[0])))) && p() && e('29,27,25,23,21');                // 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery false 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[0]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[1])))) && p() && e('0');                             // 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery false 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[1]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[0])))) && p() && e('30,29,28,27,26,25,24,23,22,21'); // 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery "`lib` = 'all'" 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[1]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[1])))) && p() && e('0');                             // 测试获取产品 1 所有分支 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery "`lib` = 'all'" 可以导入的用例
