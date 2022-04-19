#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getStoryCases();
cid=1
pid=1

测试获取需求 2 的关联用例 >> 这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4;用例库用例1;用例库用例2;用例库用例3;用例库用例4
测试获取需求 6 的关联用例 >> 这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8;用例库用例5;用例库用例6;用例库用例7;用例库用例8
测试获取需求 10 的关联用例 >> 这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12;用例库用例9;用例库用例10
测试获取需求 14 的关联用例 >> 这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16
测试获取需求 18 的关联用例 >> 这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20
测试获取需求 1 的关联用例 >> 0

*/

$storyIDList = array(2, 6, 10, 14, 18, 1);

$testcase = new testcaseTest();

r($testcase->getStoryCasesTest($storyIDList[0])) && p('1:title;2:title;3:title;4:title;401:title;402:title;403:title;404:title') && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4;用例库用例1;用例库用例2;用例库用例3;用例库用例4'); // 测试获取需求 2 的关联用例
r($testcase->getStoryCasesTest($storyIDList[1])) && p('5:title;6:title;7:title;8:title;405:title;406:title;407:title;408:title') && e('这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8;用例库用例5;用例库用例6;用例库用例7;用例库用例8'); // 测试获取需求 6 的关联用例
r($testcase->getStoryCasesTest($storyIDList[2])) && p('9:title;10:title;11:title;12:title;409:title;410:title') && e('这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12;用例库用例9;用例库用例10'); // 测试获取需求 10 的关联用例
r($testcase->getStoryCasesTest($storyIDList[3])) && p('13:title;14:title;15:title;16:title')                    && e('这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16'); // 测试获取需求 14 的关联用例
r($testcase->getStoryCasesTest($storyIDList[4])) && p('17:title;18:title;19:title;20:title')                    && e('这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20'); // 测试获取需求 18 的关联用例
r($testcase->getStoryCasesTest($storyIDList[5])) && p()                                                         && e('0');                                                                   // 测试获取需求 1 的关联用例