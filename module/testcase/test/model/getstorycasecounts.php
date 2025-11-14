#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getStoryCaseCounts();
cid=19004
pid=1

*/

$stories = array(array('1' => 1, '2' => 2 ), array('3' => 3, '4' => 4 ), array('5' => 5, '6' => 6 ), array('7' => 7, '8' => 8 ), array('9' => 9, '10' => 10 ));

$testcase = new testcaseTest();

r($testcase->getStoryCaseCountsTest($stories[0])) && p('1;2')  && e('0;4'); // 测试获取需求 1 2 的测试用例个数
r($testcase->getStoryCaseCountsTest($stories[1])) && p('3;4')  && e('0;0'); // 测试获取需求 3 4 的测试用例个数
r($testcase->getStoryCaseCountsTest($stories[2])) && p('5;6')  && e('0;4'); // 测试获取需求 5 6 的测试用例个数
r($testcase->getStoryCaseCountsTest($stories[3])) && p('7;8')  && e('0;0'); // 测试获取需求 7 8 的测试用例个数
r($testcase->getStoryCaseCountsTest($stories[4])) && p('9;10') && e('0;4'); // 测试获取需求 9 10 的测试用例个数
