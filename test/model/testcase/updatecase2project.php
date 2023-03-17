#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->updateCase2Project();
cid=1
pid=1

测试修改用例 1 产品 >> 101,2,1
测试修改用例 1 需求 >> 101,2,1;12,1,1;102,1,1
测试修改用例 3 产品 >> 101,2,3
测试修改用例 3 需求 >> 101,2,3;12,1,3;102,1,3
测试修改用例 5 产品 >> 102,1,5
测试修改用例 5 需求 >> 102,1,5;11,2,5;101,2,5
测试修改用例 7 产品 >> 102,1,7
测试修改用例 7 需求 >> 102,1,7;11,2,7;101,2,7
测试修改用例 9 产品 >> 103,1,9
测试修改用例 9 需求 >> 103,1,9;11,3,9;101,3,9

*/

$caseIDList = array('1', '3', '5', '7', '9');
$objectTypeList = array('product', 'story');
$objectIDList   = array('2', '1', '6', '2');

$testcase = new testcaseTest();

r($testcase->updateCase2ProjectTest($caseIDList[0], $objectTypeList[0], $objectIDList[0])) && p('0:project,product,case')                                               && e('101,2,1');                // 测试修改用例 1 产品
r($testcase->updateCase2ProjectTest($caseIDList[0], $objectTypeList[1], $objectIDList[2])) && p('0:project,product,case;1:project,product,case;2:project,product,case') && e('101,2,1;12,1,1;102,1,1'); // 测试修改用例 1 需求
r($testcase->updateCase2ProjectTest($caseIDList[1], $objectTypeList[0], $objectIDList[0])) && p('0:project,product,case')                                               && e('101,2,3');                // 测试修改用例 3 产品
r($testcase->updateCase2ProjectTest($caseIDList[1], $objectTypeList[1], $objectIDList[2])) && p('0:project,product,case;1:project,product,case;2:project,product,case') && e('101,2,3;12,1,3;102,1,3'); // 测试修改用例 3 需求
r($testcase->updateCase2ProjectTest($caseIDList[2], $objectTypeList[0], $objectIDList[1])) && p('0:project,product,case')                                               && e('102,1,5');                // 测试修改用例 5 产品
r($testcase->updateCase2ProjectTest($caseIDList[2], $objectTypeList[1], $objectIDList[3])) && p('0:project,product,case;1:project,product,case;2:project,product,case') && e('102,1,5;11,2,5;101,2,5'); // 测试修改用例 5 需求
r($testcase->updateCase2ProjectTest($caseIDList[3], $objectTypeList[0], $objectIDList[1])) && p('0:project,product,case')                                               && e('102,1,7');                // 测试修改用例 7 产品
r($testcase->updateCase2ProjectTest($caseIDList[3], $objectTypeList[1], $objectIDList[3])) && p('0:project,product,case;1:project,product,case;2:project,product,case') && e('102,1,7;11,2,7;101,2,7'); // 测试修改用例 7 需求
r($testcase->updateCase2ProjectTest($caseIDList[4], $objectTypeList[0], $objectIDList[1])) && p('0:project,product,case')                                               && e('103,1,9');                // 测试修改用例 9 产品
r($testcase->updateCase2ProjectTest($caseIDList[4], $objectTypeList[1], $objectIDList[3])) && p('0:project,product,case;1:project,product,case;2:project,product,case') && e('103,1,9;11,3,9;101,3,9'); // 测试修改用例 9 需求
