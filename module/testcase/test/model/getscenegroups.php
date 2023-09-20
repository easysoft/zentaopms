#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('scene')->config('modulebranchscene')->gen('100');
zdTable('module')->config('module')->gen('50');
zdTable('case')->config('modulescenecase')->gen('50');
zdTable('projectcase')->gen('10');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getSceneGroups();
cid=1
pid=1

*/

$productIdList  = array(1, 2);
$branchList     = array('all', '0');
$browseTypeList = array('noscene', 'onlyscene');
$moduleIdList   = array(0, 1);
$caseTypeList   = array('', 'install');
$orderList      = array('id_desc', 'id_asc');


$testcase = new testcaseTest();

r($testcase->getSceneGroupsTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('1,2,3'); // 获取产品 1 分支 all noscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('0');     // 获取产品 1 分支 0 noscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[0], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('1,2,3'); // 获取产品 1 分支 all onlyscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('1,3');   // 获取产品 1 分支 all noscene 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('1,2,3'); // 获取产品 1 分支 all noscene 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('1,2,3'); // 获取产品 1 分支 all noscene 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[1], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('0');     // 获取产品 1 分支 0 onlyscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('0');     // 获取产品 1 分支 0 noscene 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('0');     // 获取产品 1 分支 0 noscene 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('0');     // 获取产品 1 分支 0 noscene 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[0], $branchList[0], $browseTypeList[1], $moduleIdList[1], $caseTypeList[1], $orderList[0])) && p() && e('1,3');   // 获取产品 1 分支 all onlyscene 模块 0 用例类型 空 id_desc 的场景分组

r($testcase->getSceneGroupsTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('6,7,8,9,10'); // 获取产品 2 分支 all noscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('0');          // 获取产品 2 分支 0 noscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[0], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('6,7,8,9,10'); // 获取产品 2 分支 all onlyscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('6,8');        // 获取产品 2 分支 all noscene 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('6,7,8,9,10'); // 获取产品 2 分支 all noscene 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[0], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('6,7,8,9,10'); // 获取产品 2 分支 all noscene 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[1], $browseTypeList[1], $moduleIdList[0], $caseTypeList[0], $orderList[0])) && p() && e('0');          // 获取产品 2 分支 0 onlyscene 模块 0 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[1], $caseTypeList[0], $orderList[0])) && p() && e('0');          // 获取产品 2 分支 0 noscene 模块 1 用例类型 空 id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[1], $orderList[0])) && p() && e('0');          // 获取产品 2 分支 0 noscene 模块 0 用例类型 install id_desc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[1], $browseTypeList[0], $moduleIdList[0], $caseTypeList[0], $orderList[1])) && p() && e('0');          // 获取产品 2 分支 0 noscene 模块 0 用例类型 空 id_asc 的场景分组
r($testcase->getSceneGroupsTest($productIdList[1], $branchList[0], $browseTypeList[1], $moduleIdList[1], $caseTypeList[1], $orderList[0])) && p() && e('6,8');        // 获取产品 2 分支 all onlyscene 模块 0 用例类型 空 id_desc 的场景分组
