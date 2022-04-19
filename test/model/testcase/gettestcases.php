#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getTestCases();
cid=1
pid=1

测试查询产品1 suiteID 1 browseType all 的测试用例 >> 这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4
测试查询产品1 suiteID 1 browseType wait 的测试用例 >> 这个是测试用例1
测试查询产品1 suiteID 1 browseType bymodule 的测试用例 >> 这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4
测试查询产品1 suiteID 1 browseType needconfirm 的测试用例 >> 这个是测试用例4;这个是测试用例3;这个是测试用例2;这个是测试用例1
测试查询产品1 suiteID 1 browseType bysuite 的测试用例 >> 这个是测试用例1;这个是测试用例2
测试查询产品2 suiteID 3 browseType all 的测试用例 >> 这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8
测试查询产品2 suiteID 3 browseType wait 的测试用例 >> 这个是测试用例5
测试查询产品2 suiteID 3 browseType bymodule 的测试用例 >> 这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8
测试查询产品2 suiteID 3 browseType needconfirm 的测试用例 >> 这个是测试用例8;这个是测试用例7;这个是测试用例6;这个是测试用例5
测试查询产品2 suiteID 3 browseType bysuite 的测试用例 >> 这个是测试用例5;这个是测试用例6
测试查询产品3 suiteID 5 browseType all 的测试用例 >> 这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12
测试查询产品3 suiteID 5 browseType wait 的测试用例 >> 这个是测试用例9
测试查询产品3 suiteID 5 browseType bymodule 的测试用例 >> 这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12
测试查询产品3 suiteID 5 browseType needconfirm 的测试用例 >> 这个是测试用例12;这个是测试用例11;这个是测试用例10;这个是测试用例9
测试查询产品3 suiteID 5 browseType bysuite 的测试用例 >> 这个是测试用例9;这个是测试用例10
测试查询产品4 suiteID 7 browseType all 的测试用例 >> 这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16
测试查询产品4 suiteID 7 browseType wait 的测试用例 >> 这个是测试用例13
测试查询产品4 suiteID 7 browseType bymodule 的测试用例 >> 这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16
测试查询产品4 suiteID 7 browseType needconfirm 的测试用例 >> 这个是测试用例16;这个是测试用例15;这个是测试用例14;这个是测试用例13
测试查询产品4 suiteID 7 browseType bysuite 的测试用例 >> 这个是测试用例13;这个是测试用例14
测试查询产品5 suiteID 9 browseType all 的测试用例 >> 这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20
测试查询产品5 suiteID 9 browseType wait 的测试用例 >> 这个是测试用例17
测试查询产品5 suiteID 9 browseType bymodule 的测试用例 >> 这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20
测试查询产品5 suiteID 9 browseType needconfirm 的测试用例 >> 这个是测试用例20;这个是测试用例19;这个是测试用例18;这个是测试用例17
测试查询产品5 suiteID 9 browseType bysuite 的测试用例 >> 这个是测试用例17;这个是测试用例18

*/

$productID  = array('1', '2', '3', '4', '5');
$browseType = array('all', 'wait', 'bymodule', 'needconfirm', 'bysuite');
$suiteID    = array('1', '3', '5', '7', '9');

$testcase = new testcaseTest();

r($testcase->getTestCasesTest($productID[0], $browseType[0], $suiteID[0])) && p('1:title;2:title;3:title;4:title')     && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4');     // 测试查询产品1 suiteID 1 browseType all 的测试用例
r($testcase->getTestCasesTest($productID[0], $browseType[1], $suiteID[0])) && p('1:title')                             && e('这个是测试用例1');                                                     // 测试查询产品1 suiteID 1 browseType wait 的测试用例
r($testcase->getTestCasesTest($productID[0], $browseType[2], $suiteID[0])) && p('1:title;2:title;3:title;4:title')     && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4');     // 测试查询产品1 suiteID 1 browseType bymodule 的测试用例
r($testcase->getTestCasesTest($productID[0], $browseType[3], $suiteID[0])) && p('0:title;1:title;2:title;3:title')     && e('这个是测试用例4;这个是测试用例3;这个是测试用例2;这个是测试用例1');     // 测试查询产品1 suiteID 1 browseType needconfirm 的测试用例
r($testcase->getTestCasesTest($productID[0], $browseType[4], $suiteID[0])) && p('1:title;2:title')                     && e('这个是测试用例1;这个是测试用例2');                                     // 测试查询产品1 suiteID 1 browseType bysuite 的测试用例
r($testcase->getTestCasesTest($productID[1], $browseType[0], $suiteID[1])) && p('5:title;6:title;7:title;8:title')     && e('这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8');     // 测试查询产品2 suiteID 3 browseType all 的测试用例
r($testcase->getTestCasesTest($productID[1], $browseType[1], $suiteID[1])) && p('5:title')                             && e('这个是测试用例5');                                                     // 测试查询产品2 suiteID 3 browseType wait 的测试用例
r($testcase->getTestCasesTest($productID[1], $browseType[2], $suiteID[1])) && p('5:title;6:title;7:title;8:title')     && e('这个是测试用例5;这个是测试用例6;这个是测试用例7;这个是测试用例8');     // 测试查询产品2 suiteID 3 browseType bymodule 的测试用例
r($testcase->getTestCasesTest($productID[1], $browseType[3], $suiteID[1])) && p('0:title;1:title;2:title;3:title')     && e('这个是测试用例8;这个是测试用例7;这个是测试用例6;这个是测试用例5');     // 测试查询产品2 suiteID 3 browseType needconfirm 的测试用例
r($testcase->getTestCasesTest($productID[1], $browseType[4], $suiteID[1])) && p('5:title;6:title')                     && e('这个是测试用例5;这个是测试用例6');                                     // 测试查询产品2 suiteID 3 browseType bysuite 的测试用例
r($testcase->getTestCasesTest($productID[2], $browseType[0], $suiteID[2])) && p('9:title;10:title;11:title;12:title')  && e('这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12');  // 测试查询产品3 suiteID 5 browseType all 的测试用例
r($testcase->getTestCasesTest($productID[2], $browseType[1], $suiteID[2])) && p('9:title')                             && e('这个是测试用例9');                                                     // 测试查询产品3 suiteID 5 browseType wait 的测试用例
r($testcase->getTestCasesTest($productID[2], $browseType[2], $suiteID[2])) && p('9:title;10:title;11:title;12:title')  && e('这个是测试用例9;这个是测试用例10;这个是测试用例11;这个是测试用例12');  // 测试查询产品3 suiteID 5 browseType bymodule 的测试用例
r($testcase->getTestCasesTest($productID[2], $browseType[3], $suiteID[2])) && p('0:title;1:title;2:title;3:title')     && e('这个是测试用例12;这个是测试用例11;这个是测试用例10;这个是测试用例9');  // 测试查询产品3 suiteID 5 browseType needconfirm 的测试用例
r($testcase->getTestCasesTest($productID[2], $browseType[4], $suiteID[2])) && p('9:title;10:title')                    && e('这个是测试用例9;这个是测试用例10');                                    // 测试查询产品3 suiteID 5 browseType bysuite 的测试用例
r($testcase->getTestCasesTest($productID[3], $browseType[0], $suiteID[3])) && p('13:title;14:title;15:title;16:title') && e('这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16'); // 测试查询产品4 suiteID 7 browseType all 的测试用例
r($testcase->getTestCasesTest($productID[3], $browseType[1], $suiteID[3])) && p('13:title')                            && e('这个是测试用例13');                                                    // 测试查询产品4 suiteID 7 browseType wait 的测试用例
r($testcase->getTestCasesTest($productID[3], $browseType[2], $suiteID[3])) && p('13:title;14:title;15:title;16:title') && e('这个是测试用例13;这个是测试用例14;这个是测试用例15;这个是测试用例16'); // 测试查询产品4 suiteID 7 browseType bymodule 的测试用例
r($testcase->getTestCasesTest($productID[3], $browseType[3], $suiteID[3])) && p('0:title;1:title;2:title;3:title')     && e('这个是测试用例16;这个是测试用例15;这个是测试用例14;这个是测试用例13'); // 测试查询产品4 suiteID 7 browseType needconfirm 的测试用例
r($testcase->getTestCasesTest($productID[3], $browseType[4], $suiteID[3])) && p('13:title;14:title')                   && e('这个是测试用例13;这个是测试用例14');                                   // 测试查询产品4 suiteID 7 browseType bysuite 的测试用例
r($testcase->getTestCasesTest($productID[4], $browseType[0], $suiteID[4])) && p('17:title;18:title;19:title;20:title') && e('这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20'); // 测试查询产品5 suiteID 9 browseType all 的测试用例
r($testcase->getTestCasesTest($productID[4], $browseType[1], $suiteID[4])) && p('17:title')                            && e('这个是测试用例17');                                                    // 测试查询产品5 suiteID 9 browseType wait 的测试用例
r($testcase->getTestCasesTest($productID[4], $browseType[2], $suiteID[4])) && p('17:title;18:title;19:title;20:title') && e('这个是测试用例17;这个是测试用例18;这个是测试用例19;这个是测试用例20'); // 测试查询产品5 suiteID 9 browseType bymodule 的测试用例
r($testcase->getTestCasesTest($productID[4], $browseType[3], $suiteID[4])) && p('0:title;1:title;2:title;3:title')     && e('这个是测试用例20;这个是测试用例19;这个是测试用例18;这个是测试用例17'); // 测试查询产品5 suiteID 9 browseType needconfirm 的测试用例
r($testcase->getTestCasesTest($productID[4], $browseType[4], $suiteID[4])) && p('17:title;18:title')                   && e('这个是测试用例17;这个是测试用例18');                                   // 测试查询产品5 suiteID 9 browseType bysuite 的测试用例