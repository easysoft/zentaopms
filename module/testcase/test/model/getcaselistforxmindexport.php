#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->loadYaml('modulescenecase')->gen('200');
zenData('product')->gen('50');
zenData('scene')->gen('50');
zenData('module')->gen('1830');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getCaseListForXmindExport();
cid=18984

- 获取产品 1 模块 0 的用例 ID 列表 @1: 正常产品1, 这是一个模块1821, 这个是测试场景1 2: 正常产品1, 这是一个模块1822, 这个是测试场景2 3: 正常产品1, , 这个是测试场景3 4: 正常产品1, ,

- 获取产品 2 模块 0 的用例 ID 列表 @5: 正常产品2, 这是一个模块1825, 这个是测试场景5 6: 正常产品2, 这是一个模块1827, 这个是测试场景6 7: 正常产品2, , 这个是测试场景7 8: 正常产品2, ,

- 获取产品 1 模块 1821 的用例 ID 列表 @1: 正常产品1, 这是一个模块1821, 这个是测试场景1

- 获取产品 2 模块 1821 的用例 ID 列表 @0
- 获取产品 1 模块 1825 的用例 ID 列表 @0
- 获取产品 2 模块 1825 的用例 ID 列表 @5: 正常产品2, 这是一个模块1825, 这个是测试场景5

*/
$productIdList = array(1, 2);
$moduleIdList  = array(0, 1821, 1825);

$testcase = new testcaseTest();
r($testcase->getCaseListForXmindExportTest($productIdList[0], $moduleIdList[0])) && p() && e('1: 正常产品1, 这是一个模块1821, 这个是测试场景1 2: 正常产品1, 这是一个模块1822, 这个是测试场景2 3: 正常产品1, , 这个是测试场景3 4: 正常产品1, , 这个是测试场景4'); // 获取产品 1 模块 0 的用例 ID 列表
r($testcase->getCaseListForXmindExportTest($productIdList[1], $moduleIdList[0])) && p() && e('5: 正常产品2, 这是一个模块1825, 这个是测试场景5 6: 正常产品2, 这是一个模块1827, 这个是测试场景6 7: 正常产品2, , 这个是测试场景7 8: 正常产品2, , 这个是测试场景8'); // 获取产品 2 模块 0 的用例 ID 列表
r($testcase->getCaseListForXmindExportTest($productIdList[0], $moduleIdList[1])) && p() && e('1: 正常产品1, 这是一个模块1821, 这个是测试场景1'); // 获取产品 1 模块 1821 的用例 ID 列表
r($testcase->getCaseListForXmindExportTest($productIdList[1], $moduleIdList[1])) && p() && e('0');                                               // 获取产品 2 模块 1821 的用例 ID 列表
r($testcase->getCaseListForXmindExportTest($productIdList[0], $moduleIdList[2])) && p() && e('0');                                               // 获取产品 1 模块 1825 的用例 ID 列表
r($testcase->getCaseListForXmindExportTest($productIdList[1], $moduleIdList[2])) && p() && e('5: 正常产品2, 这是一个模块1825, 这个是测试场景5'); // 获取产品 2 模块 1825 的用例 ID 列表
