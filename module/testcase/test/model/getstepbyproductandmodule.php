#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('case')->config('modulecase')->gen('100');
zdTable('casestep')->gen('100');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getStepByProductAndModule();
cid=1
pid=1

*/

$productIdList = array(0, 1, 2, 3);
$moduleIdList  = array(0, 1821, 1825, 1829);

$testcase = new testcaseTest();

r($testcase->getStepByProductAndModuleTest($productIdList[0], $moduleIdList[0])) && p() && e('0');                                                                                                     // 获取产品 0 模块 0 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[1], $moduleIdList[0])) && p() && e('testcaseID:1 stepID: 1. testcaseID:2 stepID: 2. testcaseID:3 stepID: 3. testcaseID:4 stepID: 4.');       // 获取产品 1 模块 0 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[1], $moduleIdList[1])) && p() && e('testcaseID:1 stepID: 1.');                                                                               // 获取产品 1 模块 1821 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[1], $moduleIdList[2])) && p() && e('0');                                                                                                     // 获取产品 1 模块 1825 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[1], $moduleIdList[3])) && p() && e('0');                                                                                                     // 获取产品 1 模块 1829 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[2], $moduleIdList[0])) && p() && e('testcaseID:5 stepID: 5. testcaseID:6 stepID: 6. testcaseID:7 stepID: 7. testcaseID:8 stepID: 8.');       // 获取产品 2 模块 0 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[2], $moduleIdList[1])) && p() && e('0');                                                                                                     // 获取产品 2 模块 1821 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[2], $moduleIdList[2])) && p() && e('testcaseID:5 stepID: 5.');                                                                               // 获取产品 2 模块 1825 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[2], $moduleIdList[3])) && p() && e('0');                                                                                                     // 获取产品 2 模块 1829 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[3], $moduleIdList[0])) && p() && e('testcaseID:9 stepID: 9. testcaseID:10 stepID: 10. testcaseID:11 stepID: 11. testcaseID:12 stepID: 12.'); // 获取产品 3 模块 0 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[3], $moduleIdList[1])) && p() && e('0');                                                                                                     // 获取产品 3 模块 1821 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[3], $moduleIdList[2])) && p() && e('0');                                                                                                     // 获取产品 3 模块 1825 的步骤信息
r($testcase->getStepByProductAndModuleTest($productIdList[3], $moduleIdList[3])) && p() && e('testcaseID:10 stepID: 10.');                                                                             // 获取产品 3 模块 1829 的步骤信息
