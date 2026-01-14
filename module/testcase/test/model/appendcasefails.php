#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testresult')->gen('80');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->appendCaseFails();
cid=18951
pid=1

*/

$caseIdList = array(1, 3, 6);
$fromList   = array('testcase', 'testtask');
$taskIdList = array(1, 3, 6);

$testcase = new testcaseModelTest();

r($testcase->appendCaseFailsTest($caseIdList[0], $fromList[0], $taskIdList[0])) && p('caseFails') && e('0'); // 测试添加用例 1 testcase run 1 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[0], $fromList[0], $taskIdList[1])) && p('caseFails') && e('0'); // 测试添加用例 1 testcase run 3 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[0], $fromList[0], $taskIdList[2])) && p('caseFails') && e('0'); // 测试添加用例 1 testcase run 6 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[0], $fromList[1], $taskIdList[0])) && p('caseFails') && e('0'); // 测试添加用例 1 testtask run 1 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[0], $fromList[1], $taskIdList[1])) && p('caseFails') && e('0'); // 测试添加用例 1 testtask run 3 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[0], $fromList[1], $taskIdList[2])) && p('caseFails') && e('0'); // 测试添加用例 1 testtask run 6 的失败记录

r($testcase->appendCaseFailsTest($caseIdList[1], $fromList[0], $taskIdList[0])) && p('caseFails') && e('1'); // 测试添加用例 3 testcase run 1 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[1], $fromList[0], $taskIdList[1])) && p('caseFails') && e('1'); // 测试添加用例 3 testcase run 3 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[1], $fromList[0], $taskIdList[2])) && p('caseFails') && e('1'); // 测试添加用例 3 testcase run 6 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[1], $fromList[1], $taskIdList[0])) && p('caseFails') && e('0'); // 测试添加用例 3 testtask run 1 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[1], $fromList[1], $taskIdList[1])) && p('caseFails') && e('1'); // 测试添加用例 3 testtask run 3 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[1], $fromList[1], $taskIdList[2])) && p('caseFails') && e('0'); // 测试添加用例 3 testtask run 6 的失败记录

r($testcase->appendCaseFailsTest($caseIdList[2], $fromList[0], $taskIdList[0])) && p('caseFails') && e('2'); // 测试添加用例 6 testcase run 1 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[2], $fromList[0], $taskIdList[1])) && p('caseFails') && e('2'); // 测试添加用例 6 testcase run 3 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[2], $fromList[0], $taskIdList[2])) && p('caseFails') && e('2'); // 测试添加用例 6 testcase run 6 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[2], $fromList[1], $taskIdList[0])) && p('caseFails') && e('0'); // 测试添加用例 6 testtask run 1 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[2], $fromList[1], $taskIdList[1])) && p('caseFails') && e('0'); // 测试添加用例 6 testtask run 3 的失败记录
r($testcase->appendCaseFailsTest($caseIdList[2], $fromList[1], $taskIdList[2])) && p('caseFails') && e('2'); // 测试添加用例 6 testtask run 6 的失败记录
