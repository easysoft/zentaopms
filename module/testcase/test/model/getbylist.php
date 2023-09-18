#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('case')->gen('20');

su('admin');

/**

title=测试 testcaseModel->getByList();
cid=1
pid=1

测试获取case 1 2 3 的信息 >> 这个是测试用例1;这个是测试用例2;这个是测试用例3
测试获取case 4 5 6 的信息 >> 这个是测试用例4;这个是测试用例5;这个是测试用例6
测试获取case 7 8 9 的信息 >> 这个是测试用例7;这个是测试用例8;这个是测试用例9
测试获取case 10 11 12 的信息 >> 这个是测试用例10;这个是测试用例11;这个是测试用例12
测试获取case 13 14 15 的信息 >> 这个是测试用例13;这个是测试用例14;这个是测试用例15

*/

$caseIDList = array(array(1,2,3), array(4,5,6), array(7,8,9), array(10,11,12), array(13,14,15));
$query      = " id < 11";

$testcase = new testcaseTest();

r($testcase->getByListTest($caseIDList[0])) && p() && e('1,2,3');    // 测试获取case 1 2 3 的信息
r($testcase->getByListTest($caseIDList[1])) && p() && e('4,5,6');    // 测试获取case 4 5 6 的信息
r($testcase->getByListTest($caseIDList[2])) && p() && e('7,8,9');    // 测试获取case 7 8 9 的信息
r($testcase->getByListTest($caseIDList[3])) && p() && e('10,11,12'); // 测试获取case 10 11 12 的信息
r($testcase->getByListTest($caseIDList[4])) && p() && e('13,14,15'); // 测试获取case 13 14 15 的信息

r($testcase->getByListTest($caseIDList[0], $query)) && p() && e('1,2,3'); // 测试获取case 1 2 3 id < 11 的信息
r($testcase->getByListTest($caseIDList[1], $query)) && p() && e('4,5,6'); // 测试获取case 4 5 6 id < 11 的信息
r($testcase->getByListTest($caseIDList[2], $query)) && p() && e('7,8,9'); // 测试获取case 7 8 9 id < 11 的信息
r($testcase->getByListTest($caseIDList[3], $query)) && p() && e('10');    // 测试获取case 10 11 12 id < 11 的信息
r($testcase->getByListTest($caseIDList[4], $query)) && p() && e('0');     // 测试获取case 13 14 15 id < 11 的信息
