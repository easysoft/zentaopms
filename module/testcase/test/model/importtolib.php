#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('20');
zenData('file')->loadYaml('casefile')->gen('20');
zenData('casestep')->loadYaml('casestep')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseTao->importToLib();
timeout=0
cid=19011

- 测试将case id 1,2 导入到用例库 @1: 21,22,23,24,25,26,27,28,29,30,31,32 21,22; 2: 33 23,24;

- 测试将case id 3,4 导入到用例库 @3: 34 25,26; 4: 35 27,28;

- 测试将不存在的case id 100 导入到用例库 @0
- 测试重复将case id 1,2 导入到用例库 @1: 36,37,38,39,40,41,42,43,44,45,46,47 29,30; 2: 48 31,32;

- 测试重复将case id 3,4 导入到用例库 @3: 49 33,34; 4: 50 35,36;

- 测试重复将不存在的case id 100 导入到用例库 @0

*/

$testcase = new testcaseTest();

$caseIdList = array(array(1, 2), array(3, 4), array(100));

r($testcase->importToLibTest($caseIdList[0])) && p() && e('1: 21,22,23,24,25,26,27,28,29,30,31,32 21,22; 2: 33 23,24;'); // 测试将case id 1,2 导入到用例库
r($testcase->importToLibTest($caseIdList[1])) && p() && e('3: 34 25,26; 4: 35 27,28;');                                  // 测试将case id 3,4 导入到用例库
r($testcase->importToLibTest($caseIdList[2])) && p() && e('0');                                                          // 测试将不存在的case id 100 导入到用例库
r($testcase->importToLibTest($caseIdList[0])) && p() && e('1: 36,37,38,39,40,41,42,43,44,45,46,47 29,30; 2: 48 31,32;'); // 测试重复将case id 1,2 导入到用例库
r($testcase->importToLibTest($caseIdList[1])) && p() && e('3: 49 33,34; 4: 50 35,36;');                                  // 测试重复将case id 3,4 导入到用例库
r($testcase->importToLibTest($caseIdList[2])) && p() && e('0');                                                          // 测试重复将不存在的case id 100 导入到用例库
