#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('module')->loadYaml('module')->gen('50');
zenData('case')->loadYaml('modulescenecase')->gen('50');
zenData('projectcase')->gen('10');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getSceneGroupCases();
cid=18996

- 获取产品 1 分支 all 模块 0 用例类型 空 id_desc 的场景分组 @4: 4; 3: 3; 2: 2; 1: 1;
- 获取产品 1 分支 0 模块 0 用例类型 空 id_desc 的场景分组 @4: 4; 3: 3; 2: 2; 1: 1;
- 获取产品 1 分支 all 模块 1 用例类型 空 id_desc 的场景分组 @0
- 获取产品 1 分支 all 模块 0 用例类型 install id_desc 的场景分组 @4: 4;
- 获取产品 1 分支 all 模块 0 用例类型 空 id_asc 的场景分组 @1: 1; 2: 2; 3: 3; 4: 4;
- 获取产品 1 分支 all 模块 0 用例类型 intall id_asc 的场景分组 @4: 4;
- 获取产品 1 分支 0 模块 1 用例类型 空 id_desc 的场景分组 @0
- 获取产品 1 分支 0 模块 0 用例类型 install id_desc 的场景分组 @4: 4;
- 获取产品 1 分支 0 模块 0 用例类型 空 id_asc 的场景分组 @1: 1; 2: 2; 3: 3; 4: 4;
- 获取产品 1 分支 0 模块 0 用例类型 install id_asc 的场景分组 @4: 4;
- 获取产品 1 分支 all 模块 0 用例类型 空 id_desc 的场景分组 @8: 8; 7: 7; 6: 6; 5: 5;
- 获取产品 1 分支 0 模块 0 用例类型 空 id_desc 的场景分组 @8: 8; 7: 7; 6: 6; 5: 5;
- 获取产品 1 分支 all 模块 1 用例类型 空 id_desc 的场景分组 @0
- 获取产品 1 分支 all 模块 0 用例类型 install id_desc 的场景分组 @0
- 获取产品 1 分支 all 模块 0 用例类型 空 id_asc 的场景分组 @5: 5; 6: 6; 7: 7; 8: 8;
- 获取产品 1 分支 all 模块 0 用例类型 intall id_asc 的场景分组 @0
- 获取产品 1 分支 0 模块 1 用例类型 空 id_desc 的场景分组 @0
- 获取产品 1 分支 0 模块 0 用例类型 install id_desc 的场景分组 @0
- 获取产品 1 分支 0 模块 0 用例类型 空 id_asc 的场景分组 @5: 5; 6: 6; 7: 7; 8: 8;
- 获取产品 1 分支 0 模块 0 用例类型 install id_asc 的场景分组 @0

*/

$productIdList  = array(1, 2);
$branchList     = array('all', '0');
$moduleIdList   = array(0, 1);
$caseTypeList   = array('', 'install');
$orderList      = array('id_desc', 'id_asc');

$testcase = new testcaseTest();

r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[0], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('4: 4; 3: 3; 2: 2; 1: 1;'); // 获取产品 1 分支 all 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[1], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('4: 4; 3: 3; 2: 2; 1: 1;'); // 获取产品 1 分支 0 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[0], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('0');                       // 获取产品 1 分支 all 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[0], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('4: 4;');                   // 获取产品 1 分支 all 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[0], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('1: 1; 2: 2; 3: 3; 4: 4;'); // 获取产品 1 分支 all 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[0], $moduleIdList[0], $caseTypeList[1], $orderList[1])) && p() && e('4: 4;');                   // 获取产品 1 分支 all 模块 0 用例类型 intall id_asc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[1], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('0');                       // 获取产品 1 分支 0 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[1], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('4: 4;');                   // 获取产品 1 分支 0 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[1], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('1: 1; 2: 2; 3: 3; 4: 4;'); // 获取产品 1 分支 0 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[0], $branchList[1], $moduleIdList[0], $caseTypeList[1], $orderList[1])) && p() && e('4: 4;');                   // 获取产品 1 分支 0 模块 0 用例类型 install id_asc 的场景分组

r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[0], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('8: 8; 7: 7; 6: 6; 5: 5;'); // 获取产品 1 分支 all 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[1], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('8: 8; 7: 7; 6: 6; 5: 5;'); // 获取产品 1 分支 0 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[0], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('0');                       // 获取产品 1 分支 all 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[0], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('0');                       // 获取产品 1 分支 all 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[0], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('5: 5; 6: 6; 7: 7; 8: 8;'); // 获取产品 1 分支 all 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[0], $moduleIdList[0], $caseTypeList[1], $orderList[1])) && p() && e('0');                       // 获取产品 1 分支 all 模块 0 用例类型 intall id_asc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[1], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('0');                       // 获取产品 1 分支 0 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[1], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('0');                       // 获取产品 1 分支 0 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[1], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('5: 5; 6: 6; 7: 7; 8: 8;'); // 获取产品 1 分支 0 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupCasesTest($productIdList[1], $branchList[1], $moduleIdList[0], $caseTypeList[1], $orderList[1])) && p() && e('0');                       // 获取产品 1 分支 0 模块 0 用例类型 install id_asc 的场景分组
