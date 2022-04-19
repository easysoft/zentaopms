#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getBySuite();
cid=1
pid=1

测试获取产品 1 套件 1 的case >> 这个是测试用例1;这个是测试用例2
测试获取产品 2 套件 3 的case >> 这个是测试用例5;这个是测试用例6
测试获取产品 3 套件 5 的case >> 这个是测试用例9;这个是测试用例10
测试获取产品 4 套件 7 的case >> 这个是测试用例13;这个是测试用例14
测试获取产品 5 套件 9 的case >> 这个是测试用例17;这个是测试用例18

*/
$productIDList = array(1, 2, 3, 4, 5);
$branch        = 0;
$suiteIDList   = array('1', '3', '5', '7', '9');

$testcase = new testcaseTest();

r($testcase->getBySuiteTest($productIDList[0], $branch, $suiteIDList[0])) && p('1:title;2:title')   && e('这个是测试用例1;这个是测试用例2');   // 测试获取产品 1 套件 1 的case
r($testcase->getBySuiteTest($productIDList[1], $branch, $suiteIDList[1])) && p('5:title;6:title')   && e('这个是测试用例5;这个是测试用例6');   // 测试获取产品 2 套件 3 的case
r($testcase->getBySuiteTest($productIDList[2], $branch, $suiteIDList[2])) && p('9:title;10:title')  && e('这个是测试用例9;这个是测试用例10');  // 测试获取产品 3 套件 5 的case
r($testcase->getBySuiteTest($productIDList[3], $branch, $suiteIDList[3])) && p('13:title;14:title') && e('这个是测试用例13;这个是测试用例14'); // 测试获取产品 4 套件 7 的case
r($testcase->getBySuiteTest($productIDList[4], $branch, $suiteIDList[4])) && p('17:title;18:title') && e('这个是测试用例17;这个是测试用例18'); // 测试获取产品 5 套件 9 的case