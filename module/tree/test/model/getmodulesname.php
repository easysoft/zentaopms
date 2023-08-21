#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('module')->config('module', true)->gen(10);
zdTable('branch')->config('branch', true)->gen(10);

/**

title=测试 treeModel->getModulesName();
timeout=0
cid=1

*/

$moduleIdList[0] = array(1, 2);
$moduleIdList[1] = array(3);
$moduleIdList[2] = array(5);
$moduleIdList[3] = array(6);
$moduleIdList[4] = array(7);
$moduleIdList[5] = array(10);

global $tester;
$treeModule = $tester->loadModel('tree');

r($treeModule->getModulesName($moduleIdList[0], false)) && p('1')  && e('模块1');  // 获取父模块的名称
r($treeModule->getModulesName($moduleIdList[1], false)) && p('3')  && e('模块3');  // 获取子模块的名称
r($treeModule->getModulesName($moduleIdList[2], false)) && p('5')  && e('模块5');  // 获取普通模块的名称
r($treeModule->getModulesName($moduleIdList[3], false)) && p('6')  && e('模块6');  // 获取分支父模块的名称
r($treeModule->getModulesName($moduleIdList[4], false)) && p('7')  && e('模块7');  // 获取分支子模块的名称
r($treeModule->getModulesName($moduleIdList[5], false)) && p('10') && e('模块10'); // 获取分支普通模块的名称

r($treeModule->getModulesName($moduleIdList[0], true)) && p('1')  && e('/模块1');       // 获取父模块的全路径名称
r($treeModule->getModulesName($moduleIdList[1], true)) && p('3')  && e('/模块1/模块3'); // 获取子模块的全路径名称
r($treeModule->getModulesName($moduleIdList[2], true)) && p('5')  && e('/模块5');       // 获取普通模块的全路径名称
r($treeModule->getModulesName($moduleIdList[3], true)) && p('6')  && e('/模块6');       // 获取分支父模块的全路径名称
r($treeModule->getModulesName($moduleIdList[4], true)) && p('7')  && e('/模块6/模块7'); // 获取分支子模块的全路径名称
r($treeModule->getModulesName($moduleIdList[5], true)) && p('10') && e('/模块10');      // 获取分支普通模块的全路径名称

r($treeModule->getModulesName($moduleIdList[0], false, true)) && p('1')  && e('模块1');  // 获取父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[1], false, true)) && p('3')  && e('模块3');  // 获取子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[2], false, true)) && p('5')  && e('模块5');  // 获取普通模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[3], false, true)) && p('6')  && e('模块6');  // 获取分支父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[4], false, true)) && p('7')  && e('模块7');  // 获取分支子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[5], false, true)) && p('10') && e('模块10'); // 获取分支普通模块的以分支名为前缀的名称

r($treeModule->getModulesName($moduleIdList[0], true, true)) && p('1')  && e('/模块1');             // 获取父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[1], true, true)) && p('3')  && e('/模块1/模块3');       // 获取子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[2], true, true)) && p('5')  && e('/模块5');             // 获取普通模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[3], true, true)) && p('6')  && e('/分支1/模块6');       // 获取分支父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[4], true, true)) && p('7')  && e('/分支1/模块6/模块7'); // 获取分支子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[5], true, true)) && p('10') && e('/分支1/模块10');      // 获取分支普通模块的以分支名为前缀的名称
