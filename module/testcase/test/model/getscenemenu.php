#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('scene')->config('moduletreescene')->gen('20');
zdTable('branch')->gen('10');
zdTable('product')->gen('50');
zdTable('user')->gen('1');

su('admin');

global $tester;
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();

/**

title=测试 testcaseModel->getSceneMenu();
cid=1
pid=1

*/

$productIdList = array(1, 41);
$moduleIdList  = array(0, 1, 2);
$sceneIdList   = array(0, 6, 16);
$branchIdList  = array('all', '0', 1);
$currentScene  = array(0, 6, 16);
$emptyMenu     = array(false, true);

$testcase = new testcaseTest();

r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[0], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景1,/场景1/场景2,/场景3,/场景4,/场景5,/场景6,/场景6/场景7,/场景6/场景7/场景8,/场景9,/场景10');    // 测试获取产品 1 模块 0 起始场景 0 分支 all 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[1], $sceneIdList[0], $branchIdList[0], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景6,/场景6/场景7,/场景6/场景7/场景8,/场景9,/场景10');                                             // 测试获取产品 1 模块 1 起始场景 0 分支 all 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[1], $branchIdList[0], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景6,/场景6/场景7,/场景6/场景7/场景8');                                                            // 测试获取产品 1 模块 0 起始场景 6 分支 all 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[1], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景1,/场景1/场景2,/场景3,/场景4,/场景5,/场景6,/场景6/场景7,/场景6/场景7/场景8,/场景9,/场景10');    // 测试获取产品 1 模块 0 起始场景 0 分支 '0' 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[2], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景1,/场景1/场景2,/场景3,/场景4,/场景5,/场景6,/场景6/场景7,/场景6/场景7/场景8,/场景9,/场景10');    // 测试获取产品 1 模块 0 起始场景 0 分支 1 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[0], $currentScene[1], $emptyMenu[0])) && p() && e('/,/场景1,/场景1/场景2,/场景3,/场景4,/场景5,/场景9,/场景10');                                           // 测试获取产品 1 模块 0 起始场景 0 分支 all 当前场景 6 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[0], $sceneIdList[0], $branchIdList[0], $currentScene[0], $emptyMenu[1])) && p() && e('/,/场景1,/场景1/场景2,/场景3,/场景4,/场景5,/场景6,/场景6/场景7,/场景6/场景7/场景8,/场景9,/场景10,空'); // 测试获取产品 1 模块 0 起始场景 0 分支 all 当前场景 0 有空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[1], $sceneIdList[1], $branchIdList[1], $currentScene[1], $emptyMenu[0])) && p() && e('/,/场景7,/场景7/场景8');                                                                               // 测试获取产品 1 模块 1 起始场景 6 分支 '0' 当前场景 6 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[0], $moduleIdList[1], $sceneIdList[1], $branchIdList[1], $currentScene[1], $emptyMenu[0])) && p() && e('/,/场景7,/场景7/场景8');                                                                               // 测试获取产品 1 模块 1 起始场景 6 分支 '0' 当前场景 6 有空值 的场景数组

r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[0], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景11,/场景11/场景12,/场景11/场景12/场景13,/场景11/场景12/场景13/场景14,/场景11/场景15,/场景16,/场景16/场景17,/场景18,/场景18/场景19,/场景18/场景19/场景20');    // 测试获取产品 41 模块 0 起始场景 0 分支 all 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[2], $sceneIdList[0], $branchIdList[0], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景16,/场景16/场景17,/场景18,/场景18/场景19,/场景18/场景19/场景20');                                                                                             // 测试获取产品 41 模块 2 起始场景 0 分支 all 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[2], $branchIdList[0], $currentScene[0], $emptyMenu[0])) && p() && e('/,/场景16,/场景16/场景17');                                                                                                                                          // 测试获取产品 41 模块 0 起始场景 6 分支 all 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[2], $currentScene[0], $emptyMenu[0])) && p() && e('/,/分支1/场景16,/分支1/场景16/场景17,/分支1/场景18,/分支1/场景18/场景19,/分支1/场景18/场景19/场景20');                                                               // 测试获取产品 41 模块 0 起始场景 0 分支 '0' 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[2], $currentScene[0], $emptyMenu[0])) && p() && e('/,/分支1/场景16,/分支1/场景16/场景17,/分支1/场景18,/分支1/场景18/场景19,/分支1/场景18/场景19/场景20');                                                               // 测试获取产品 41 模块 0 起始场景 0 分支 1 当前场景 0 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[0], $currentScene[2], $emptyMenu[0])) && p() && e('/,/场景11,/场景11/场景12,/场景11/场景12/场景13,/场景11/场景12/场景13/场景14,/场景11/场景15,/场景18,/场景18/场景19,/场景18/场景19/场景20');                           // 测试获取产品 41 模块 0 起始场景 0 分支 all 当前场景 6 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[0], $sceneIdList[0], $branchIdList[0], $currentScene[0], $emptyMenu[1])) && p() && e('/,/场景11,/场景11/场景12,/场景11/场景12/场景13,/场景11/场景12/场景13/场景14,/场景11/场景15,/场景16,/场景16/场景17,/场景18,/场景18/场景19,/场景18/场景19/场景20,空'); // 测试获取产品 41 模块 0 起始场景 0 分支 all 当前场景 0 有空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[2], $sceneIdList[2], $branchIdList[2], $currentScene[2], $emptyMenu[0])) && p() && e('/,/分支1/场景17');                                                                                                                                                   // 测试获取产品 41 模块 2 起始场景 6 分支 '0' 当前场景 6 无空值 的场景数组
r($testcase->getSceneMenuTest($productIdList[1], $moduleIdList[2], $sceneIdList[2], $branchIdList[2], $currentScene[2], $emptyMenu[0])) && p() && e('/,/分支1/场景17');                                                                                                                                                   // 测试获取产品 41 模块 2 起始场景 6 分支 '0' 当前场景 6 有空值 的场景数组
