#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->gen(45);
zdTable('branch')->gen(20);
zdTable('case')->config('case')->gen(50);
zdTable('module')->config('module_import')->gen(50);
zdTable('userquery')->config('userquery')->gen(1);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testsuiteModel->getCanImportCases();
cid=1
pid=1


*/

$productIdList  = array(1, 41);
$libIdList      = array(0, 1);
$branchList     = array('all', 1);
$orderByList    = array('id_desc', 'id_asc');
$browseTypeList = array('', 'bysearch');
$queryIdList    = array(0, 1);
$testsuiteQuery = array(false, "`lib` = 'all'");

$tester->loadModel('testcase');

r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[0], $orderByList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[1], $branchList[0], $orderByList[0])))) && p() && e('30,28,26,24,22'); // 测试获取产品 1 用例库 1 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[1], $orderByList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 1 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[1], $branchList[1], $orderByList[0])))) && p() && e('30,28,26,24,22'); // 测试获取产品 1 用例库 1 分支 1 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[0], $orderByList[1])))) && p() && e('21,23,25,27,29'); // 测试获取产品 1 用例库 0 分支 all 排序 id_asc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[1], $branchList[1], $orderByList[1])))) && p() && e('22,24,26,28,30'); // 测试获取产品 1 用例库 1 分支 1 排序 id_asc 可以导入的用例

r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[0], $branchList[0], $orderByList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 41 用例库 0 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[1], $branchList[0], $orderByList[0])))) && p() && e('30,28,26,24,22'); // 测试获取产品 41 用例库 1 分支 all 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[0], $branchList[1], $orderByList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 41 用例库 0 分支 1 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[1], $branchList[1], $orderByList[0])))) && p() && e('30,28,26,24,22'); // 测试获取产品 41 用例库 1 分支 1 排序 id_desc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[0], $branchList[0], $orderByList[1])))) && p() && e('21,23,25,27,29'); // 测试获取产品 41 用例库 0 分支 all 排序 id_asc 可以导入的用例
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[1], $libIdList[1], $branchList[1], $orderByList[1])))) && p() && e('22,24,26,28,30'); // 测试获取产品 41 用例库 1 分支 1 排序 id_asc 可以导入的用例

$tester->session->set('testsuiteQuery', $testsuiteQuery[0]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery false 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[0]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[1])))) && p() && e('0');              // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery false 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[1]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery "`lib` = 'all'" 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[1]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[0], $orderByList[0], null, $browseTypeList[1], $queryIdList[1])))) && p() && e('0');              // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery "`lib` = 'all'" 可以导入的用例

$tester->session->set('testsuiteQuery', $testsuiteQuery[0]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[1], $orderByList[0], null, $browseTypeList[1], $queryIdList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery false 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[0]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[1], $orderByList[0], null, $browseTypeList[1], $queryIdList[1])))) && p() && e('0');              // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery false 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[1]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[1], $orderByList[0], null, $browseTypeList[1], $queryIdList[0])))) && p() && e('29,27,25,23,21'); // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 0 testsuiteQuery "`lib` = 'all'" 可以导入的用例
$tester->session->set('testsuiteQuery', $testsuiteQuery[1]);
r(implode(',', array_keys($tester->testcase->getCanImportCases($productIdList[0], $libIdList[0], $branchList[1], $orderByList[0], null, $browseTypeList[1], $queryIdList[1])))) && p() && e('0');              // 测试获取产品 1 用例库 0 分支 all 排序 id_desc browseType bysearch queryID 1 testsuiteQuery "`lib` = 'all'" 可以导入的用例
