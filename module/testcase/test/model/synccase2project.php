#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('20');
zenData('story')->gen('20');
zenData('project')->gen('100', true, false);
zenData('project')->loadYaml('execution')->gen('100', false, false);
zenData('projectcase')->gen(0);
zenData('projectstory')->gen('4');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->syncCase2Project();
timeout=0
cid=19025

- 测试同步用例 1  到关联项目中 @11,101

- 测试同步用例 5  到关联项目中 @12,102

- 测试同步用例 9  到关联项目中 @103
- 测试同步用例 13 到关联项目中 @104
- 测试同步用例 17 到关联项目中 @105

*/

$caseIDList = array(1, 5, 9, 13, 17);

$testcase = new testcaseTest();

r($testcase->syncCase2ProjectTest($caseIDList[0])) && p() && e('11,101'); // 测试同步用例 1  到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[1])) && p() && e('12,102'); // 测试同步用例 5  到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[2])) && p() && e('103'); // 测试同步用例 9  到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[3])) && p() && e('104'); // 测试同步用例 13 到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[4])) && p() && e('105'); // 测试同步用例 17 到关联项目中
