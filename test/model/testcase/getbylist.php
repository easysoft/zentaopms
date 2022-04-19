#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
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

$caseIDList = array('1,2,3', '4,5,6', '7,8,9', '10,11,12', '13,14,15');

$testcase = new testcaseTest();

r($testcase->getByListTest($caseIDList[0])) && p('1:title;2:title;3:title')    && e('这个是测试用例1;这个是测试用例2;这个是测试用例3');    // 测试获取case 1 2 3 的信息
r($testcase->getByListTest($caseIDList[0])) && p('4:title;5:title;6:title')    && e('这个是测试用例4;这个是测试用例5;这个是测试用例6');    // 测试获取case 4 5 6 的信息
r($testcase->getByListTest($caseIDList[0])) && p('7:title;8:title;9:title')    && e('这个是测试用例7;这个是测试用例8;这个是测试用例9');    // 测试获取case 7 8 9 的信息
r($testcase->getByListTest($caseIDList[0])) && p('10:title;11:title;12:title') && e('这个是测试用例10;这个是测试用例11;这个是测试用例12'); // 测试获取case 10 11 12 的信息
r($testcase->getByListTest($caseIDList[0])) && p('13:title;14:title;15:title') && e('这个是测试用例13;这个是测试用例14;这个是测试用例15'); // 测试获取case 13 14 15 的信息