#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getExecutionCases();
cid=1
pid=1

测试获取执行 101 的用例 >> 这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4
测试获取执行 101 状态 wait 的用例 >> 1
测试获取执行 101 状态 normal 的用例 >> 1
测试获取执行 101 状态 blocked 的用例 >> 1
测试获取执行 101 状态 investigate 的用例 >> 1
测试获取执行 101 状态 needconfirm 的用例 >> 4
测试获取执行 102 的用例 >> 这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8
测试获取执行 102 状态 wait 的用例 >> 1
测试获取执行 102 状态 normal 的用例 >> 1
测试获取执行 102 状态 blocked 的用例 >> 1
测试获取执行 102 状态 investigate 的用例 >> 1
测试获取执行 102 状态 needconfirm 的用例 >> 4
测试获取执行 103 的用例 >> 这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12
测试获取执行 103 状态 wait 的用例 >> 1
测试获取执行 103 状态 normal 的用例 >> 1
测试获取执行 103 状态 blocked 的用例 >> 1
测试获取执行 103 状态 investigate 的用例 >> 1
测试获取执行 103 状态 needconfirm 的用例 >> 4
测试获取执行 104 的用例 >> 这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16
测试获取执行 104 状态 wait 的用例 >> 1
测试获取执行 104 状态 normal 的用例 >> 1
测试获取执行 104 状态 blocked 的用例 >> 1
测试获取执行 104 状态 investigate 的用例 >> 1
测试获取执行 104 状态 needconfirm 的用例 >> 4
测试获取执行 105 的用例 >> 这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20
测试获取执行 105 状态 wait 的用例 >> 1
测试获取执行 105 状态 normal 的用例 >> 1
测试获取执行 105 状态 blocked 的用例 >> 1
测试获取执行 105 状态 investigate 的用例 >> 1
测试获取执行 105 状态 needconfirm 的用例 >> 4

*/

$executionIDList = array(101, 102, 103, 104, 105);
$orderBy         = 'id_desc';
$pager           = null;
$browseTypeList  = array('all', 'wait', 'normal', 'blocked', 'investigate', 'needconfirm');

$testcase = new testcaseTest();

r($testcase->getExecutionCasesTest($executionIDList[0], $orderBy, $pager, $browseTypeList[0])) && p('1:title;2:title;3:title;4:title')     && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4'); // 测试获取执行 101 的用例
r($testcase->getExecutionCasesTest($executionIDList[0], $orderBy, $pager, $browseTypeList[1])) && p()                                      && e('1'); // 测试获取执行 101 状态 wait 的用例
r($testcase->getExecutionCasesTest($executionIDList[0], $orderBy, $pager, $browseTypeList[2])) && p()                                      && e('1'); // 测试获取执行 101 状态 normal 的用例
r($testcase->getExecutionCasesTest($executionIDList[0], $orderBy, $pager, $browseTypeList[3])) && p()                                      && e('1'); // 测试获取执行 101 状态 blocked 的用例
r($testcase->getExecutionCasesTest($executionIDList[0], $orderBy, $pager, $browseTypeList[4])) && p()                                      && e('1'); // 测试获取执行 101 状态 investigate 的用例
r($testcase->getExecutionCasesTest($executionIDList[0], $orderBy, $pager, $browseTypeList[5])) && p()                                      && e('4'); // 测试获取执行 101 状态 needconfirm 的用例
r($testcase->getExecutionCasesTest($executionIDList[1], $orderBy, $pager, $browseTypeList[0])) && p('5:title;6:title;7:title;8:title')     && e('这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8'); // 测试获取执行 102 的用例
r($testcase->getExecutionCasesTest($executionIDList[1], $orderBy, $pager, $browseTypeList[1])) && p()                                      && e('1'); // 测试获取执行 102 状态 wait 的用例
r($testcase->getExecutionCasesTest($executionIDList[1], $orderBy, $pager, $browseTypeList[2])) && p()                                      && e('1'); // 测试获取执行 102 状态 normal 的用例
r($testcase->getExecutionCasesTest($executionIDList[1], $orderBy, $pager, $browseTypeList[3])) && p()                                      && e('1'); // 测试获取执行 102 状态 blocked 的用例
r($testcase->getExecutionCasesTest($executionIDList[1], $orderBy, $pager, $browseTypeList[4])) && p()                                      && e('1'); // 测试获取执行 102 状态 investigate 的用例
r($testcase->getExecutionCasesTest($executionIDList[1], $orderBy, $pager, $browseTypeList[5])) && p()                                      && e('4'); // 测试获取执行 102 状态 needconfirm 的用例
r($testcase->getExecutionCasesTest($executionIDList[2], $orderBy, $pager, $browseTypeList[0])) && p('9:title;10:title;11:title;12:title')  && e('这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12'); // 测试获取执行 103 的用例
r($testcase->getExecutionCasesTest($executionIDList[2], $orderBy, $pager, $browseTypeList[1])) && p()                                      && e('1'); // 测试获取执行 103 状态 wait 的用例
r($testcase->getExecutionCasesTest($executionIDList[2], $orderBy, $pager, $browseTypeList[2])) && p()                                      && e('1'); // 测试获取执行 103 状态 normal 的用例
r($testcase->getExecutionCasesTest($executionIDList[2], $orderBy, $pager, $browseTypeList[3])) && p()                                      && e('1'); // 测试获取执行 103 状态 blocked 的用例
r($testcase->getExecutionCasesTest($executionIDList[2], $orderBy, $pager, $browseTypeList[4])) && p()                                      && e('1'); // 测试获取执行 103 状态 investigate 的用例
r($testcase->getExecutionCasesTest($executionIDList[2], $orderBy, $pager, $browseTypeList[5])) && p()                                      && e('4'); // 测试获取执行 103 状态 needconfirm 的用例
r($testcase->getExecutionCasesTest($executionIDList[3], $orderBy, $pager, $browseTypeList[0])) && p('13:title;14:title;15:title;16:title') && e('这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16'); // 测试获取执行 104 的用例
r($testcase->getExecutionCasesTest($executionIDList[3], $orderBy, $pager, $browseTypeList[1])) && p()                                      && e('1'); // 测试获取执行 104 状态 wait 的用例
r($testcase->getExecutionCasesTest($executionIDList[3], $orderBy, $pager, $browseTypeList[2])) && p()                                      && e('1'); // 测试获取执行 104 状态 normal 的用例
r($testcase->getExecutionCasesTest($executionIDList[3], $orderBy, $pager, $browseTypeList[3])) && p()                                      && e('1'); // 测试获取执行 104 状态 blocked 的用例
r($testcase->getExecutionCasesTest($executionIDList[3], $orderBy, $pager, $browseTypeList[4])) && p()                                      && e('1'); // 测试获取执行 104 状态 investigate 的用例
r($testcase->getExecutionCasesTest($executionIDList[3], $orderBy, $pager, $browseTypeList[5])) && p()                                      && e('4'); // 测试获取执行 104 状态 needconfirm 的用例
r($testcase->getExecutionCasesTest($executionIDList[4], $orderBy, $pager, $browseTypeList[0])) && p('17:title;18:title;19:title;20:title') && e('这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20'); // 测试获取执行 105 的用例
r($testcase->getExecutionCasesTest($executionIDList[4], $orderBy, $pager, $browseTypeList[1])) && p()                                      && e('1'); // 测试获取执行 105 状态 wait 的用例
r($testcase->getExecutionCasesTest($executionIDList[4], $orderBy, $pager, $browseTypeList[2])) && p()                                      && e('1'); // 测试获取执行 105 状态 normal 的用例
r($testcase->getExecutionCasesTest($executionIDList[4], $orderBy, $pager, $browseTypeList[3])) && p()                                      && e('1'); // 测试获取执行 105 状态 blocked 的用例
r($testcase->getExecutionCasesTest($executionIDList[4], $orderBy, $pager, $browseTypeList[4])) && p()                                      && e('1'); // 测试获取执行 105 状态 investigate 的用例
r($testcase->getExecutionCasesTest($executionIDList[4], $orderBy, $pager, $browseTypeList[5])) && p()                                      && e('4'); // 测试获取执行 105 状态 needconfirm 的用例