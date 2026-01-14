#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->loadYaml('module_caselib')->gen('60');

/**

title=测试 testcaseModel->importCaseRelatedModules();
cid=19010

- 测试将模块 1 导入用例库 1 中 @61
- 测试将模块 2 导入用例库 1 中 @12
- 测试将模块 3 导入用例库 1 中 @13
- 测试将模块 6 导入用例库 1 中 @62
- 测试将模块 22 导入用例库 2 中 @32
- 测试将模块 23 导入用例库 2 中 @33
- 测试将模块 24 导入用例库 2 中 @64
- 测试将模块 26 导入用例库 2 中 @65
- 测试将模块 43 导入用例库 3 中 @53
- 测试将模块 44 导入用例库 3 中 @66
- 测试将模块 45 导入用例库 3 中 @67
- 测试将模块 46 导入用例库 3 中 @68

*/

$libIdList     = array(1, 2, 3);
$oldModuleList = array(1, 2, 3, 6, 22, 23, 24, 26, 43, 44, 45, 46);
$maxOrder      = '50';

$testcase = new testcaseModelTest();

r($testcase->importCaseRelatedModulesTest($libIdList[0], $oldModuleList[0]))  && p() && e('61'); // 测试将模块 1 导入用例库 1 中
r($testcase->importCaseRelatedModulesTest($libIdList[0], $oldModuleList[1]))  && p() && e('12'); // 测试将模块 2 导入用例库 1 中
r($testcase->importCaseRelatedModulesTest($libIdList[0], $oldModuleList[2]))  && p() && e('13'); // 测试将模块 3 导入用例库 1 中
r($testcase->importCaseRelatedModulesTest($libIdList[0], $oldModuleList[3]))  && p() && e('62'); // 测试将模块 6 导入用例库 1 中
r($testcase->importCaseRelatedModulesTest($libIdList[1], $oldModuleList[4]))  && p() && e('32'); // 测试将模块 22 导入用例库 2 中
r($testcase->importCaseRelatedModulesTest($libIdList[1], $oldModuleList[5]))  && p() && e('33'); // 测试将模块 23 导入用例库 2 中
r($testcase->importCaseRelatedModulesTest($libIdList[1], $oldModuleList[6]))  && p() && e('64'); // 测试将模块 24 导入用例库 2 中
r($testcase->importCaseRelatedModulesTest($libIdList[1], $oldModuleList[7]))  && p() && e('65'); // 测试将模块 26 导入用例库 2 中
r($testcase->importCaseRelatedModulesTest($libIdList[2], $oldModuleList[8]))  && p() && e('53'); // 测试将模块 43 导入用例库 3 中
r($testcase->importCaseRelatedModulesTest($libIdList[2], $oldModuleList[9]))  && p() && e('66'); // 测试将模块 44 导入用例库 3 中
r($testcase->importCaseRelatedModulesTest($libIdList[2], $oldModuleList[10])) && p() && e('67'); // 测试将模块 45 导入用例库 3 中
r($testcase->importCaseRelatedModulesTest($libIdList[2], $oldModuleList[11])) && p() && e('68'); // 测试将模块 46 导入用例库 3 中
