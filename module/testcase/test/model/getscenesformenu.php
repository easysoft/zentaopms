#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('scene')->config('modulescene')->gen('20');
zdTable('user')->gen('1');

su('admin');

global $tester;
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();


/**

title=测试 testcaseModel->getScenesForMenu();
cid=1
pid=1

*/

$productIdList = array(0, 1, 2);
$moduleIdList  = array(0, 1821, 1825);
$sceneIdList   = array(0, 1, 6);
$branchIdList  = array('all', '', '0');
$currentScene  = 1;

$testcase = new testcaseTest();

r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0]))                   && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试获取产品 0 模块 0 起始场景 0 的场景
r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[0])) && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试获取产品 0 模块 0 起始场景 0 分支 all 的场景
r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[1])) && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试获取产品 0 模块 0 起始场景 0 分支 '' 的场景
r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[2])) && p() && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试获取产品 0 模块 0 起始场景 0 分支 0 的场景

r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[1]))                   && p() && e('1,2,3,4'); // 测试获取产品 0 模块 0 起始场景 1 的场景
r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[1], $branchIdList[2])) && p() && e('1,2,3,4'); // 测试获取产品 0 模块 0 起始场景 1 分支 0 的场景

r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[2]))                   && p() && e('6,7,8'); // 测试获取产品 0 模块 0 起始场景 6 的场景
r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[2], $branchIdList[2])) && p() && e('6,7,8'); // 测试获取产品 0 模块 0 起始场景 6 分支 0 的场景


r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0]))                   && p() && e('1,2,3,4'); // 测试获取产品 1 模块 0 起始场景 0 的场景
r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[0])) && p() && e('1,2,3,4'); // 测试获取产品 1 模块 0 起始场景 0 分支 all 的场景
r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[1])) && p() && e('1,2,3,4'); // 测试获取产品 1 模块 0 起始场景 0 分支 '' 的场景
r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[2])) && p() && e('1,2,3,4'); // 测试获取产品 1 模块 0 起始场景 0 分支 0 的场景

r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[1]))                   && p() && e('1,2,3,4'); // 测试获取产品 1 模块 0 起始场景 1 的场景
r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[1], $branchIdList[2])) && p() && e('1,2,3,4'); // 测试获取产品 1 模块 0 起始场景 1 分支 0 的场景

r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[1], $sceneIdList[1]))                   && p() && e('1'); // 测试获取产品 1 模块 1821 起始场景 1 的场景
r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[1], $sceneIdList[1], $branchIdList[2])) && p() && e('1'); // 测试获取产品 1 模块 1821 起始场景 1 分支 0 的场景


r($testcase->getScenesForMenuTest($productIdList[2], $moduleIdList[0], $sceneIdList[0]))                   && p() && e('5,6,7,8'); // 测试获取产品 2 模块 0 起始场景 0 的场景
r($testcase->getScenesForMenuTest($productIdList[2], $moduleIdList[0], $sceneIdList[0], $branchIdList[0])) && p() && e('5,6,7,8'); // 测试获取产品 2 模块 0 起始场景 0 分支 all 的场景
r($testcase->getScenesForMenuTest($productIdList[2], $moduleIdList[0], $sceneIdList[0], $branchIdList[1])) && p() && e('5,6,7,8'); // 测试获取产品 2 模块 0 起始场景 0 分支 '' 的场景
r($testcase->getScenesForMenuTest($productIdList[2], $moduleIdList[0], $sceneIdList[0], $branchIdList[2])) && p() && e('5,6,7,8'); // 测试获取产品 2 模块 0 起始场景 0 分支 0 的场景

r($testcase->getScenesForMenuTest($productIdList[2], $moduleIdList[0], $sceneIdList[2]))                   && p() && e('6,7,8'); // 测试获取产品 2 模块 0 起始场景 6 的场景
r($testcase->getScenesForMenuTest($productIdList[2], $moduleIdList[0], $sceneIdList[2], $branchIdList[2])) && p() && e('6,7,8'); // 测试获取产品 2 模块 0 起始场景 6 分支 0 的场景

r($testcase->getScenesForMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[2], $currentScene)) && p() && e('5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'); // 测试获取产品 0 模块 0 起始场景 0 分支 0 的场景 排除 1
r($testcase->getScenesForMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[2], $currentScene)) && p() && e('0'); // 测试获取产品 1 模块 0 起始场景 0 分支 0 的场景 排除 1 
