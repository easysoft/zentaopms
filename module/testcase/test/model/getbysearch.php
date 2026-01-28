#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen('20');
zenData('projectcase')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getByOpenedBy();
timeout=0
cid=18980

- 测试搜索 qa 下 项目 0 产品 0 下的用例 @20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1

- 测试搜索 qa 下 项目 0 产品 0 分支 all 下的用例 @20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1

- 测试搜索 qa 下 项目 0 产品 0 auto auto 下的用例 @0
- 测试搜索 qa 下 项目 0 产品 0 orderBy id_asc 下的用例 @1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20

- 测试搜索 qa 下 项目 0 产品 0 分支 all auto auto  下的用例 @0
- 测试搜索 qa 下 项目 0 产品 0 分支 all orderBy id_asc 下的用例 @1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20

- 测试搜索 qa 下 项目 0 产品 0 auto auto orderBy id_asc 下的用例 @0
- 测试搜索 qa 下 项目 0 产品 0 分支 all auto auto orderBy id_asc 下的用例 @0
- 测试搜索 qa 下 项目 101 产品 0 下的用例 @20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1

- 测试搜索 qa 下 项目 0 产品 0 下的用例 @20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1

- 测试搜索 qa 下 项目 0 产品 1 下的用例 @4,3,2,1

- 测试搜索 qa 下 项目 101 产品 1 下的用例 @4,3,2,1

- 测试搜索 project 下 项目 0 产品 0 下的用例 @0
- 测试搜索 project 下 项目 101 产品 0 下的用例 @4,3,2,1

- 测试搜索 project 下 项目 0 产品 1 下的用例 @0
- 测试搜索 project 下 项目 101 产品 1 下的用例 @4,3,2,1

*/

$tabList       = array('qa', 'project');
$projectIdList = array('0', '101');
$productIdList = array('0', '1');
$branchIdList  = array(0, 'all');
$autoList      = array('no', 'auto');
$orderByList   = array('id_desc', 'id_asc');

$testcase = new testcaseModelTest();

r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[0], 0, $autoList[0], $orderByList[0])) && p() && e('20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试搜索 qa 下 项目 0 产品 0 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[1], 0, $autoList[0], $orderByList[0])) && p() && e('20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试搜索 qa 下 项目 0 产品 0 分支 all 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[0], 0, $autoList[1], $orderByList[0])) && p() && e('0');                                                  // 测试搜索 qa 下 项目 0 产品 0 auto auto 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[0], 0, $autoList[0], $orderByList[1])) && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试搜索 qa 下 项目 0 产品 0 orderBy id_asc 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[1], 0, $autoList[1], $orderByList[0])) && p() && e('0');                                                  // 测试搜索 qa 下 项目 0 产品 0 分支 all auto auto  下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[1], 0, $autoList[0], $orderByList[1])) && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试搜索 qa 下 项目 0 产品 0 分支 all orderBy id_asc 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[0], 0, $autoList[1], $orderByList[1])) && p() && e('0');                                                  // 测试搜索 qa 下 项目 0 产品 0 auto auto orderBy id_asc 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[1], 0, $autoList[1], $orderByList[1])) && p() && e('0');                                                  // 测试搜索 qa 下 项目 0 产品 0 分支 all auto auto orderBy id_asc 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[1], $productIdList[0], $branchIdList[0], 0, $autoList[0], $orderByList[0])) && p() && e('20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试搜索 qa 下 项目 101 产品 0 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[0], $branchIdList[0], 0, $autoList[0], $orderByList[0])) && p() && e('20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1'); // 测试搜索 qa 下 项目 0 产品 0 下的用例

r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[1])) && p() && e('4,3,2,1'); // 测试搜索 qa 下 项目 0 产品 1 下的用例
r($testcase->getBySearchTest($tabList[0], $projectIdList[1], $productIdList[1])) && p() && e('4,3,2,1'); // 测试搜索 qa 下 项目 101 产品 1 下的用例

r($testcase->getBySearchTest($tabList[1], $projectIdList[0], $productIdList[0])) && p() && e('0');       // 测试搜索 project 下 项目 0 产品 0 下的用例
r($testcase->getBySearchTest($tabList[1], $projectIdList[1], $productIdList[0])) && p() && e('4,3,2,1'); // 测试搜索 project 下 项目 101 产品 0 下的用例
r($testcase->getBySearchTest($tabList[1], $projectIdList[0], $productIdList[1])) && p() && e('0');       // 测试搜索 project 下 项目 0 产品 1 下的用例
r($testcase->getBySearchTest($tabList[1], $projectIdList[1], $productIdList[1])) && p() && e('4,3,2,1'); // 测试搜索 project 下 项目 101 产品 1 下的用例
