#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('40');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getStoryCases();
cid=19005
pid=1

*/

$storyIDList = array(2, 6, 10, 14, 18, 1);

$testcase = new testcaseTest();

r($testcase->getStoryCasesTest($storyIDList[0])) && p() && e('1,2,3,4');     // 测试获取需求 2 的关联用例
r($testcase->getStoryCasesTest($storyIDList[1])) && p() && e('5,6,7,8');     // 测试获取需求 6 的关联用例
r($testcase->getStoryCasesTest($storyIDList[2])) && p() && e('9,10,11,12');  // 测试获取需求 10 的关联用例
r($testcase->getStoryCasesTest($storyIDList[3])) && p() && e('13,14,15,16'); // 测试获取需求 14 的关联用例
r($testcase->getStoryCasesTest($storyIDList[4])) && p() && e('17,18,19,20'); // 测试获取需求 18 的关联用例
r($testcase->getStoryCasesTest($storyIDList[5])) && p() && e('0');           // 测试获取需求 1 的关联用例
