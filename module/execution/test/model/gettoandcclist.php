#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

/**

title=测试 executionModel::getToAndCcList();
timeout=0
cid=0

- 获取执行 101 的通知人员
 -  @user4
 - 属性1 @~~
- 获取执行 102 的通知人员
 -  @user5
 - 属性1 @~~
- 获取执行 103 的通知人员
 -  @user6
 - 属性1 @~~
- 获取执行 104 的通知人员
 -  @user7
 - 属性1 @~~
- 获取执行 105 的通知人员
 -  @user8
 - 属性1 @~~

*/

su('admin');
zenData('project')->loadYaml('execution')->gen(20);
zenData('team')->loadYaml('team')->gen(10);
zenData('user')->gen(110);

$executionIdList = array(101, 102, 103, 104, 105);

$executionTester = new executionTest();

r($executionTester->getToAndCcListTest($executionIdList[0])) && p('0|1', '|') && e('user4|~~'); // 获取执行 101 的通知人员
r($executionTester->getToAndCcListTest($executionIdList[1])) && p('0|1', '|') && e('user5|~~'); // 获取执行 102 的通知人员
r($executionTester->getToAndCcListTest($executionIdList[2])) && p('0|1', '|') && e('user6|~~'); // 获取执行 103 的通知人员
r($executionTester->getToAndCcListTest($executionIdList[3])) && p('0|1', '|') && e('user7|~~'); // 获取执行 104 的通知人员
r($executionTester->getToAndCcListTest($executionIdList[4])) && p('0|1', '|') && e('user8|~~'); // 获取执行 105 的通知人员
