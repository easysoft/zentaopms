#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('projectcase')->gen(10);

/**

title=测试 testcaseModel->getExecutionCases();
timeout=0
cid=18989

- 测试获取执行 101 的用例
 - 第1条的title属性 @这个是测试用例1
 - 第2条的title属性 @这个是测试用例2
 - 第3条的title属性 @这个是测试用例3
 - 第4条的title属性 @这个是测试用例4
- 测试获取执行101 下 wait 状态的用例数 @1
- 测试获取执行101 下 normal 状态的用例数 @1
- 测试获取执行101 下 blocked 状态的用例数 @1
- 测试获取执行101 下 investigate 状态的用例数 @1
- 测试获取执行101 下 needconfirm 状态的用例数 @4

*/

$executionIDList = array(101, 102, 103, 104, 105);
$browseTypeList  = array('all', 'wait', 'normal', 'blocked', 'investigate', 'needconfirm');

$testcase = new testcaseModelTest();

r($testcase->getExecutionCasesTest($browseTypeList[0], $executionIDList[0])) && p('1:title;2:title;3:title;4:title') && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4'); // 测试获取执行 101 的用例
r($testcase->getExecutionCasesTest($browseTypeList[1], $executionIDList[0])) && p()                                  && e(1); //测试获取执行101 下 wait 状态的用例数
r($testcase->getExecutionCasesTest($browseTypeList[2], $executionIDList[0])) && p()                                  && e(1); //测试获取执行101 下 normal 状态的用例数
r($testcase->getExecutionCasesTest($browseTypeList[3], $executionIDList[0])) && p()                                  && e(1); //测试获取执行101 下 blocked 状态的用例数
r($testcase->getExecutionCasesTest($browseTypeList[4], $executionIDList[0])) && p()                                  && e(1); //测试获取执行101 下 investigate 状态的用例数
r($testcase->getExecutionCasesTest($browseTypeList[5], $executionIDList[0])) && p()                                  && e(4); //测试获取执行101 下 needconfirm 状态的用例数