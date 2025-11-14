#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->loadYaml('modulecase')->gen('300');
zenData('projectcase')->gen('300');
zenData('story')->gen('300');
zenData('projectstory')->gen('300');
zenData('suitecase')->gen('300');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getTestCases();
timeout=0
cid=19006

- 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 all browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @1
- 测试查询产品 1 分支 all browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 all browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0,1,2

- 测试查询产品 1 分支 all browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 all browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 0 browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @1
- 测试查询产品 1 分支 0 browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 0 browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0,1,2

- 测试查询产品 1 分支 0 browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 0 browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 all browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 0 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 1 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 all browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例 @1
- 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例 @1
- 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例 @3
- 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例 @3
- 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例 @1,2,4

- 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例 @1,2,4

- 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例 @0
- 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 all browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @161
- 测试查询产品 41 分支 all browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 all browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 0 browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @161
- 测试查询产品 41 分支 0 browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 0 browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 0 browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 0 browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 all browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 0 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 1 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例 @162
- 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例 @162
- 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例 @161,163,164

- 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例 @161,163,164

- 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例 @0

*/

$productIdList  = array(1, 41);
$branchList     = array('all', 0, 1);
$browseTypeList = array('all', 'wait', 'bymodule', 'needconfirm', 'bysuite', 'bysearch');
$moduleIdList   = array(0, 1821);
$caseTypeList   = array('', 'feature');
$unitList       = array('no', 'unit');
$orderByList    = array('id_desc', 'id_asc');

$testcase = new testcaseTest();

r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('1');           // 测试查询产品 1 分支 all browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[2], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 all browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[3], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0,1,2');       // 测试查询产品 1 分支 all browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[4], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 all browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[5], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 all browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('1');           // 测试查询产品 1 分支 0 browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[2], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 0 browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[3], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0,1,2');       // 测试查询产品 1 分支 0 browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[4], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 0 browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[5], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 0 browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 all browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 0 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[2], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $unitList[0], $orderByList[0])) && p() && e('1');           // 测试查询产品 1 分支 all browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $unitList[0], $orderByList[0])) && p() && e('1');           // 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[1], $orderByList[0])) && p() && e('3');           // 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[1], $orderByList[0])) && p() && e('3');           // 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[1], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[1])) && p() && e('1,2,4');       // 测试查询产品 1 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[1])) && p() && e('1,2,4');       // 测试查询产品 1 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例
r($testcase->getTestCasesTest($productIdList[0], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[1])) && p() && e('0');           // 测试查询产品 1 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('161');         // 测试查询产品 41 分支 all browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[2], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 all browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[3], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 all browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[4], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 all browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[5], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 all browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('161');         // 测试查询产品 41 分支 0 browseType wait 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[2], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 0 browseType bymodule 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[3], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 0 browseType needconfirm 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[4], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 0 browseType bysuite 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[5], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 0 browseType bysearch 模块 0 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 all browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 0 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[2], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 browseType all 模块 1821 用例类型 空 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 all browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $unitList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 feature 自动化 no 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[1], $orderByList[0])) && p() && e('162');         // 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[1], $orderByList[0])) && p() && e('162');         // 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[1], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 空 自动化 unit 排序 id_desc 的测试用例

r($testcase->getTestCasesTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[1])) && p() && e('161,163,164'); // 测试查询产品 41 分支 all browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[1])) && p() && e('161,163,164'); // 测试查询产品 41 分支 0 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例
r($testcase->getTestCasesTest($productIdList[1], $branchList[2], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $unitList[0], $orderByList[1])) && p() && e('0');           // 测试查询产品 41 分支 1 browseType all 模块 0 用例类型 空 自动化 no 排序 id_asc 的测试用例
