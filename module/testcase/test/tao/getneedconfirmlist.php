#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->loadYaml('modulebranchcase')->gen('200');
zenData('projectcase')->gen('300');
zendata('story')->loadYaml('versionstory')->gen('200');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getNeedConfirmList();
timeout=0
cid=19040

- 测试查询产品 1 分支 all 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 all 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例 @1
- 测试查询产品 1 分支 all 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 all 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @3
- 测试查询产品 1 分支 all 模块 0 自动化 no 用例类型 install 排序 id_desc 的测试用例 @4
- 测试查询产品 1 分支 all 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例 @1,2,4

- 测试查询产品 1 分支 0 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例 @4,2,1

- 测试查询产品 1 分支 0 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例 @1
- 测试查询产品 1 分支 0 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 0 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @3
- 测试查询产品 1 分支 0 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例 @1,2,4

- 测试查询产品 1 分支 1 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 1 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 1 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 1 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 1 分支 1 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例 @0
- 测试查询产品 41 分支 all 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例 @164,163,161

- 测试查询产品 41 分支 all 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例 @161
- 测试查询产品 41 分支 all 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @162
- 测试查询产品 41 分支 all 模块 0 自动化 no 用例类型 install 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 all 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例 @161,163,164

- 测试查询产品 41 分支 0 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例 @161
- 测试查询产品 41 分支 0 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例 @161
- 测试查询产品 41 分支 0 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 0 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @162
- 测试查询产品 41 分支 0 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例 @161
- 测试查询产品 41 分支 1 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例 @164,163

- 测试查询产品 41 分支 1 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 1 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 1 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例 @0
- 测试查询产品 41 分支 1 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例 @163,164

*/

$productIdList  = array(1, 41);
$branchList     = array('all', 0, 1);
$moduleIdList   = array(0, 1821);
$unitList       = array('no', 'unit');
$caseTypeList   = array('', 'install');
$orderByList    = array('id_desc', 'id_asc');

$testcase = new testcaseTest();

r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[0], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 all 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[0], $moduleIdList[1], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('1');           // 测试查询产品 1 分支 all 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[0], $moduleIdList[1], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 all 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[0], $moduleIdList[0], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('3');           // 测试查询产品 1 分支 all 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[0], $moduleIdList[0], $unitList[0], $caseTypeList[1], $orderByList[0])) && p() && e('4');           // 测试查询产品 1 分支 all 模块 0 自动化 no 用例类型 install 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[0], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[1])) && p() && e('1,2,4');       // 测试查询产品 1 分支 all 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例

r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[1], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('4,2,1');       // 测试查询产品 1 分支 0 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[1], $moduleIdList[1], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('1');           // 测试查询产品 1 分支 0 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[1], $moduleIdList[1], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 0 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[1], $moduleIdList[0], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('3');           // 测试查询产品 1 分支 0 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[1], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[1])) && p() && e('1,2,4');       // 测试查询产品 1 分支 0 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例

r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[2], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[2], $moduleIdList[1], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[2], $moduleIdList[1], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[2], $moduleIdList[0], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 1 分支 1 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[0], $branchList[2], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[1])) && p() && e('0');           // 测试查询产品 1 分支 1 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例

r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[0], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('164,163,161'); // 测试查询产品 41 分支 all 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[0], $moduleIdList[1], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('161');         // 测试查询产品 41 分支 all 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[0], $moduleIdList[1], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 all 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[0], $moduleIdList[0], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('162');         // 测试查询产品 41 分支 all 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[0], $moduleIdList[0], $unitList[0], $caseTypeList[1], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 all 模块 0 自动化 no 用例类型 install 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[0], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[1])) && p() && e('161,163,164'); // 测试查询产品 41 分支 all 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例

r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[1], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('161');         // 测试查询产品 41 分支 0 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[1], $moduleIdList[1], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('161');         // 测试查询产品 41 分支 0 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[1], $moduleIdList[1], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 0 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[1], $moduleIdList[0], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('162');         // 测试查询产品 41 分支 0 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[1], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[1])) && p() && e('161');         // 测试查询产品 41 分支 0 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例

r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[2], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('164,163');     // 测试查询产品 41 分支 1 模块 0 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[2], $moduleIdList[1], $unitList[0], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 模块 1821 自动化 no 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[2], $moduleIdList[1], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 模块 1821 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[2], $moduleIdList[0], $unitList[1], $caseTypeList[0], $orderByList[0])) && p() && e('0');           // 测试查询产品 41 分支 1 模块 0 自动化 unit 用例类型 空 排序 id_desc 的测试用例
r($testcase->getNeedConfirmListTest($productIdList[1], $branchList[2], $moduleIdList[0], $unitList[0], $caseTypeList[0], $orderByList[1])) && p() && e('163,164');     // 测试查询产品 41 分支 1 模块 0 自动化 no 用例类型 空 排序 id_asc 的测试用例