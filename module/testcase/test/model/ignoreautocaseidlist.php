#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

/**

title=测试 testcaseModel->ignoreAutoCaseIdList();
cid=19009

- 传入空数组 @0
- 传入不存在的用例 @0
- 传入重复的用例 @1
- 传入不重复非自动化用例 @1;2
- 传入自动化的用例 @0
- 传入既有自动化用例又有非自动化用例 @1;2

*/

zenData('case')->loadYaml('ignoreautocase')->gen('50');

global $tester;
$testcase = $tester->loadModel('testcase');
r(count($testcase->ignoreAutoCaseIdList(array())))                && p() && e('0');    // 传入空数组
r(count($testcase->ignoreAutoCaseIdList(array(100, 101))))        && p() && e('0');    // 传入不存在的用例
r(implode(';', $testcase->ignoreAutoCaseIdList(array(1, 1))))     && p() && e('1');    // 传入重复的用例
r(implode(';', $testcase->ignoreAutoCaseIdList(array(1, 2))))     && p() && e('1;2');  // 传入不重复非自动化用例
r(count($testcase->ignoreAutoCaseIdList(array(3))))               && p() && e('0');    // 传入自动化的用例
r(implode(';', $testcase->ignoreAutoCaseIdList(array(1, 2, 3))))  && p() && e('1;2');  // 传入既有自动化用例又有非自动化用例
