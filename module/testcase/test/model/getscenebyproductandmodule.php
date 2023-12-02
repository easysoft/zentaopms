#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('scene')->config('modulescene')->gen('100');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getSceneByProductAndModule();
cid=1
pid=1

*/

$productIdList = array(0, 1, 2, 3);
$moduleIdList  = array(0, 1821, 1825, 1829);

$testcase = new testcaseTest();

r($testcase->getSceneByProductAndModuleTest($productIdList[0], $moduleIdList[0])) && p() && e('0');                                          // 获取产品 0 模块 0 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[1], $moduleIdList[0])) && p() && e('sceneMaps:1 2 3 4;topScenes:1;');             // 获取产品 1 模块 0 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[1], $moduleIdList[1])) && p() && e('sceneMaps:1;topScenes:1;');                   // 获取产品 1 模块 1821 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[1], $moduleIdList[2])) && p() && e('0');                                          // 获取产品 1 模块 1825 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[1], $moduleIdList[3])) && p() && e('0');                                          // 获取产品 1 模块 1829 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[2], $moduleIdList[0])) && p() && e('sceneMaps:5 6 7 8;topScenes:5 6;');           // 获取产品 2 模块 0 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[2], $moduleIdList[1])) && p() && e('0');                                          // 获取产品 2 模块 1821 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[2], $moduleIdList[2])) && p() && e('sceneMaps:5;topScenes:5;');                   // 获取产品 2 模块 1825 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[2], $moduleIdList[3])) && p() && e('0');                                          // 获取产品 2 模块 1829 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[3], $moduleIdList[0])) && p() && e('sceneMaps:9 10 11 12;topScenes:9 10 11 12;'); // 获取产品 3 模块 0 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[3], $moduleIdList[1])) && p() && e('0');                                          // 获取产品 3 模块 1821 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[3], $moduleIdList[2])) && p() && e('0');                                          // 获取产品 3 模块 1825 的场景信息
r($testcase->getSceneByProductAndModuleTest($productIdList[3], $moduleIdList[3])) && p() && e('sceneMaps:10;topScenes:10;');                 // 获取产品 3 模块 1829 的场景信息
