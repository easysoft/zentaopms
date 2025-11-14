#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->loadYaml('modulecase')->gen('100');
zenData('story')->gen('10');

su('admin');

/**

title=测试 testcaseModel->getModuleCases();
timeout=0
cid=18992

- 测试获取产品1 分支 0 模块 0 browse 空 auto 空 用例类型 空 的cases @4,2,1

- 测试获取产品1 分支 all 模块 0 browse 空 auto 空 用例类型 空 的cases @4,2,1

- 测试获取产品1 分支 0 模块 1821 1822 browse 空 auto 空 用例类型 空 的cases @2,1

- 测试获取产品1 分支 0 模块 0 browse wait auto 空 用例类型 空 的cases @1
- 测试获取产品1 分支 0 模块 0 browse 空 auto no 用例类型 空 的cases @4,2,1

- 测试获取产品1 分支 0 模块 0 browse 空 auto unit 用例类型 空 的cases @3
- 测试获取产品1 分支 0 模块 0 browse 空 auto 空 用例类型 feature 的cases @1
- 测试获取产品1 分支 all 模块 1821 1822 browse wait auto no 用例类型 feature 的cases @1
- 测试获取产品1 分支 all 模块 1821 1822 browse wait auto unit 用例类型 feature 的cases @0
- 测试获取产品2 分支 0 模块 0 browse 空 auto 空 用例类型 空 的cases @8,7,5

- 测试获取产品2 分支 all 模块 0 browse 空 auto 空 用例类型 空 的cases @8,7,5

- 测试获取产品2 分支 0 模块 1825 1827 browse 空 auto 空 用例类型 空 的cases @0
- 测试获取产品2 分支 0 模块 0 browse wait auto 空 用例类型 空 的cases @5
- 测试获取产品2 分支 0 模块 0 browse 空 auto no 用例类型 空 的cases @8,7,5

- 测试获取产品2 分支 0 模块 0 browse 空 auto unit 用例类型 空 的cases @6
- 测试获取产品2 分支 0 模块 0 browse 空 auto 空 用例类型 feature 的cases @8
- 测试获取产品2 分支 all 模块 1825 1827 browse wait auto no 用例类型 feature 的cases @0
- 测试获取产品2 分支 all 模块 1825 1827 browse wait auto unit 用例类型 feature 的cases @0
- 测试获取不存在的产品 分支 0 模块 0 browse 空 auto 空 用例类型 空 的cases @0
- 测试获取不存在的产品 分支 0 模块 0 browse 空 auto no 用例类型 空 的cases @0
- 测试获取不存在的产品 分支 0 模块 0 browse 空 auto unit 用例类型 空 的cases @0

*/

$productIDList = array(1, 2, 10001);
$branchList    = array(0, 'all');
$moduleIdList  = array(0, array(1821,1822), array(1825,1827), array(1829,1832));
$browseList    = array('', 'wait');
$autoList      = array('', 'no', 'unit');
$caseTypeList  = array('', 'feature');

$testcase = new testcaseTest();

r($testcase->getModuleCasesTest($productIDList[0], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('4,2,1'); // 测试获取产品1 分支 0 模块 0 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[1], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('4,2,1'); // 测试获取产品1 分支 all 模块 0 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[0], $moduleIdList[1], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('2,1');   // 测试获取产品1 分支 0 模块 1821 1822 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[0], $moduleIdList[0], $browseList[1], $autoList[0], $caseTypeList[0])) && p() && e('1');     // 测试获取产品1 分支 0 模块 0 browse wait auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[1], $caseTypeList[0])) && p() && e('4,2,1'); // 测试获取产品1 分支 0 模块 0 browse 空 auto no 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[2], $caseTypeList[0])) && p() && e('3');     // 测试获取产品1 分支 0 模块 0 browse 空 auto unit 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[1])) && p() && e('1');     // 测试获取产品1 分支 0 模块 0 browse 空 auto 空 用例类型 feature 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[1], $moduleIdList[1], $browseList[1], $autoList[1], $caseTypeList[1])) && p() && e('1');     // 测试获取产品1 分支 all 模块 1821 1822 browse wait auto no 用例类型 feature 的cases
r($testcase->getModuleCasesTest($productIDList[0], $branchList[1], $moduleIdList[1], $browseList[1], $autoList[2], $caseTypeList[1])) && p() && e('0');     // 测试获取产品1 分支 all 模块 1821 1822 browse wait auto unit 用例类型 feature 的cases

r($testcase->getModuleCasesTest($productIDList[1], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('8,7,5'); // 测试获取产品2 分支 0 模块 0 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[1], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('8,7,5'); // 测试获取产品2 分支 all 模块 0 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[0], $moduleIdList[1], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('0');     // 测试获取产品2 分支 0 模块 1825 1827 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[0], $moduleIdList[0], $browseList[1], $autoList[0], $caseTypeList[0])) && p() && e('5');     // 测试获取产品2 分支 0 模块 0 browse wait auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[1], $caseTypeList[0])) && p() && e('8,7,5'); // 测试获取产品2 分支 0 模块 0 browse 空 auto no 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[2], $caseTypeList[0])) && p() && e('6');     // 测试获取产品2 分支 0 模块 0 browse 空 auto unit 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[1])) && p() && e('8');     // 测试获取产品2 分支 0 模块 0 browse 空 auto 空 用例类型 feature 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[1], $moduleIdList[1], $browseList[1], $autoList[1], $caseTypeList[1])) && p() && e('0');     // 测试获取产品2 分支 all 模块 1825 1827 browse wait auto no 用例类型 feature 的cases
r($testcase->getModuleCasesTest($productIDList[1], $branchList[1], $moduleIdList[1], $browseList[1], $autoList[2], $caseTypeList[1])) && p() && e('0');     // 测试获取产品2 分支 all 模块 1825 1827 browse wait auto unit 用例类型 feature 的cases

r($testcase->getModuleCasesTest($productIDList[2], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[0], $caseTypeList[0])) && p() && e('0'); // 测试获取不存在的产品 分支 0 模块 0 browse 空 auto 空 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[2], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[1], $caseTypeList[0])) && p() && e('0'); // 测试获取不存在的产品 分支 0 模块 0 browse 空 auto no 用例类型 空 的cases
r($testcase->getModuleCasesTest($productIDList[2], $branchList[0], $moduleIdList[0], $browseList[0], $autoList[2], $caseTypeList[0])) && p() && e('0');     // 测试获取不存在的产品 分支 0 模块 0 browse 空 auto unit 用例类型 空 的cases
