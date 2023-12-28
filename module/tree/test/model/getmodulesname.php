#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getModulesName();
timeout=0
cid=1

- 获取父模块的名称属性1 @模块1
- 获取子模块的名称属性6 @模块6
- 获取普通模块的名称属性11 @模块11
- 获取分支父模块的名称属性16 @模块16
- 获取分支子模块的名称属性19 @模块19
- 获取分支普通模块的名称属性18 @模块18
- 获取父模块的全路径名称属性1 @/模块1
- 获取子模块的全路径名称属性6 @/模块1/模块6
- 获取普通模块的全路径名称属性11 @/模块11
- 获取分支父模块的全路径名称属性16 @/模块16
- 获取分支子模块的全路径名称属性19 @/模块16/模块19
- 获取分支普通模块的全路径名称属性18 @/模块18
- 获取父模块的以分支名为前缀的名称属性1 @模块1
- 获取子模块的以分支名为前缀的名称属性6 @模块6
- 获取普通模块的以分支名为前缀的名称属性11 @模块11
- 获取分支父模块的以分支名为前缀的名称属性16 @模块16
- 获取分支子模块的以分支名为前缀的名称属性19 @模块19
- 获取分支普通模块的以分支名为前缀的名称属性18 @模块18
- 获取父模块的以分支名为前缀的名称属性1 @/模块1
- 获取子模块的以分支名为前缀的名称属性6 @/模块1/模块6
- 获取普通模块的以分支名为前缀的名称属性11 @/分支1/模块11

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('module')->config('module', true)->gen(20);
zdTable('branch')->config('branch', true)->gen(10);

$moduleIdList[0] = array(1);
$moduleIdList[1] = array(6);
$moduleIdList[2] = array(11);
$moduleIdList[3] = array(16);
$moduleIdList[4] = array(19);
$moduleIdList[5] = array(18);

global $tester;
$treeModule = $tester->loadModel('tree');

r($treeModule->getModulesName($moduleIdList[0], false)) && p('1')  && e('模块1');  // 获取父模块的名称
r($treeModule->getModulesName($moduleIdList[1], false)) && p('6')  && e('模块6');  // 获取子模块的名称
r($treeModule->getModulesName($moduleIdList[2], false)) && p('11') && e('模块11'); // 获取普通模块的名称
r($treeModule->getModulesName($moduleIdList[3], false)) && p('16') && e('模块16'); // 获取分支父模块的名称
r($treeModule->getModulesName($moduleIdList[4], false)) && p('19') && e('模块19'); // 获取分支子模块的名称
r($treeModule->getModulesName($moduleIdList[5], false)) && p('18') && e('模块18'); // 获取分支普通模块的名称

r($treeModule->getModulesName($moduleIdList[0], true)) && p('1')  && e('/模块1');         // 获取父模块的全路径名称
r($treeModule->getModulesName($moduleIdList[1], true)) && p('6')  && e('/模块1/模块6');   // 获取子模块的全路径名称
r($treeModule->getModulesName($moduleIdList[2], true)) && p('11') && e('/模块11');        // 获取普通模块的全路径名称
r($treeModule->getModulesName($moduleIdList[3], true)) && p('16') && e('/模块16');        // 获取分支父模块的全路径名称
r($treeModule->getModulesName($moduleIdList[4], true)) && p('19') && e('/模块16/模块19'); // 获取分支子模块的全路径名称
r($treeModule->getModulesName($moduleIdList[5], true)) && p('18') && e('/模块18');        // 获取分支普通模块的全路径名称

r($treeModule->getModulesName($moduleIdList[0], false, true)) && p('1')  && e('模块1');  // 获取父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[1], false, true)) && p('6')  && e('模块6');  // 获取子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[2], false, true)) && p('11') && e('模块11'); // 获取普通模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[3], false, true)) && p('16') && e('模块16'); // 获取分支父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[4], false, true)) && p('19') && e('模块19'); // 获取分支子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[5], false, true)) && p('18') && e('模块18'); // 获取分支普通模块的以分支名为前缀的名称

r($treeModule->getModulesName($moduleIdList[0], true, true)) && p('1')  && e('/模块1');        // 获取父模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[1], true, true)) && p('6')  && e('/模块1/模块6');  // 获取子模块的以分支名为前缀的名称
r($treeModule->getModulesName($moduleIdList[2], true, true)) && p('11') && e('/分支1/模块11'); // 获取普通模块的以分支名为前缀的名称