#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getModuleProjectCases();
cid=1
pid=1

测试获取产品1的case >> 这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4
测试获取产品1 module 1821 1822 的case >> 0
测试获取产品2的case >> 这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8
测试获取产品2 module 1825 2827 的case >> 0
测试获取产品3的case >> 这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12
测试获取产品3 module 1829 2832 的case >> 0
测试获取产品4的case >> 这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16
测试获取产品4 module 1834 2835 的case >> 0
测试获取产品5的case >> 这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20
测试获取产品5 module 1838 2840 的case >> 0

*/
$productIDList = array(1, 2, 3, 4, 5);
$branch        = 0;
$moduleIDList  = array('1821,1822', '1825,1827', '1829,1832', '1834,2835', '1838,1840');

$testcase = new testcaseTest();

r($testcase->getModuleProjectCasesTest($productIDList[0]))                            && p('1:title;2:title;3:title;4:title')     && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4');   // 测试获取产品1的case
r($testcase->getModuleProjectCasesTest($productIDList[0], $branch, $moduleIDList[0])) && p()                                      && e('0');                                                                 // 测试获取产品1 module 1821 1822 的case
r($testcase->getModuleProjectCasesTest($productIDList[1]))                            && p('5:title;6:title;7:title;8:title')     && e('这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8');   // 测试获取产品2的case
r($testcase->getModuleProjectCasesTest($productIDList[1], $branch, $moduleIDList[1])) && p()                                      && e('0');                                                                 // 测试获取产品2 module 1825 2827 的case
r($testcase->getModuleProjectCasesTest($productIDList[2]))                            && p('9:title;10:title;11:title;12:title')  && e('这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12');  // 测试获取产品3的case
r($testcase->getModuleProjectCasesTest($productIDList[2], $branch, $moduleIDList[2])) && p()                                      && e('0');                                                                 // 测试获取产品3 module 1829 2832 的case
r($testcase->getModuleProjectCasesTest($productIDList[3]))                            && p('13:title;14:title;15:title;16:title') && e('这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16'); // 测试获取产品4的case
r($testcase->getModuleProjectCasesTest($productIDList[3], $branch, $moduleIDList[3])) && p()                                      && e('0');                                                                 // 测试获取产品4 module 1834 2835 的case
r($testcase->getModuleProjectCasesTest($productIDList[4]))                            && p('17:title;18:title;19:title;20:title') && e('这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20'); // 测试获取产品5的case
r($testcase->getModuleProjectCasesTest($productIDList[4], $branch, $moduleIDList[4])) && p()                                      && e('0');                                                                 // 测试获取产品5 module 1838 2840 的case