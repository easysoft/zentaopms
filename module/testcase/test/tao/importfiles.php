#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('20');
zenData('file')->loadYaml('casefile')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseTao->importFiles();
cid=19047
pid=1

- 测试获取case id 21 插入 case id 1 的步骤 @21,22
- 测试获取case id 22 插入 case id 2 的步骤 @23,24
- 测试获取case id 23 插入 case id 3 的步骤 @25,26
- 测试获取case id 24 插入 case id 4 的步骤 @27,28
- 测试获取case id 25 插入 case id 5 的步骤 @29,30
- 测试获取case id 26 插入 不存在的 case id 的步骤 @0

*/

$testcase = new testcaseTest();

$caseIdList    = array(21, 22, 23, 24, 25, 26);
$oldCaseIdList = array(1, 2, 3, 4, 5, 30);

r($testcase->importFilesTest($caseIdList[0], $oldCaseIdList[0])) && p() && e('21,22'); // 测试获取case id 21 插入 case id 1 的步骤
r($testcase->importFilesTest($caseIdList[1], $oldCaseIdList[1])) && p() && e('23,24'); // 测试获取case id 22 插入 case id 2 的步骤
r($testcase->importFilesTest($caseIdList[2], $oldCaseIdList[2])) && p() && e('25,26'); // 测试获取case id 23 插入 case id 3 的步骤
r($testcase->importFilesTest($caseIdList[3], $oldCaseIdList[3])) && p() && e('27,28'); // 测试获取case id 24 插入 case id 4 的步骤
r($testcase->importFilesTest($caseIdList[4], $oldCaseIdList[4])) && p() && e('29,30'); // 测试获取case id 25 插入 case id 5 的步骤
r($testcase->importFilesTest($caseIdList[5], $oldCaseIdList[5])) && p() && e('0');     // 测试获取case id 26 插入 不存在的 case id 的步骤
