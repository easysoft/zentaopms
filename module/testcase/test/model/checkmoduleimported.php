#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->loadYaml('module_caselib')->gen('60');

/**

title=测试 testcaseModel->checkModuleImported();
cid=18968

- 测试用例库 1 中 module 1 是否已经导入 @0
- 测试用例库 1 中 module 2 是否已经导入 @12
- 测试用例库 1 中 module 3 是否已经导入 @13
- 测试用例库 1 中 module 6 是否已经导入 @0
- 测试用例库 2 中 module 22 是否已经导入 @32
- 测试用例库 2 中 module 23 是否已经导入 @33
- 测试用例库 2 中 module 24 是否已经导入 @0
- 测试用例库 2 中 module 26 是否已经导入 @0
- 测试用例库 3 中 module 43 是否已经导入 @53
- 测试用例库 3 中 module 44 是否已经导入 @0
- 测试用例库 3 中 module 45 是否已经导入 @0
- 测试用例库 3 中 module 46 是否已经导入 @0


*/

$libIdList     = array(1, 2, 3);
$oldModuleList = array(1, 2, 3, 6, 22, 23, 24, 26, 43, 44, 45, 46);

$testcase = new testcaseModelTest();

r($testcase->checkModuleImportedTest($libIdList[0], $oldModuleList[0]))  && p() && e('0');  // 测试用例库 1 中 module 1 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[0], $oldModuleList[1]))  && p() && e('12'); // 测试用例库 1 中 module 2 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[0], $oldModuleList[2]))  && p() && e('13'); // 测试用例库 1 中 module 3 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[0], $oldModuleList[3]))  && p() && e('0');  // 测试用例库 1 中 module 6 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[1], $oldModuleList[4]))  && p() && e('32'); // 测试用例库 2 中 module 22 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[1], $oldModuleList[5]))  && p() && e('33'); // 测试用例库 2 中 module 23 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[1], $oldModuleList[6]))  && p() && e('0');  // 测试用例库 2 中 module 24 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[1], $oldModuleList[7]))  && p() && e('0');  // 测试用例库 2 中 module 26 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[2], $oldModuleList[8]))  && p() && e('53'); // 测试用例库 3 中 module 43 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[2], $oldModuleList[9]))  && p() && e('0');  // 测试用例库 3 中 module 44 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[2], $oldModuleList[10])) && p() && e('0');  // 测试用例库 3 中 module 45 是否已经导入
r($testcase->checkModuleImportedTest($libIdList[2], $oldModuleList[11])) && p() && e('0');  // 测试用例库 3 中 module 46 是否已经导入
