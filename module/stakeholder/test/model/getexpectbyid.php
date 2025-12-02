#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getExpectByID();
cid=18432

- 获取ID=0的期望信息 @0
- 获取ID=1的期望信息
 - 属性userID @11
 - 属性expect @期望1
 - 属性progress @进度1
 - 属性project @11
- 获取ID不存在的期望信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stakeholder.unittest.class.php';

zenData('user')->gen(5);
zenData('expect')->loadYaml('expect')->gen(1);

$idList = array(0, 1, 2);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getExpectByIDTest($idList[0])) && p()                                 && e('0');                 // 获取ID=0的期望信息
r($stakeholderTester->getExpectByIDTest($idList[1])) && p('userID,expect,progress,project') && e('11,期望1,进度1,11'); // 获取ID=1的期望信息
r($stakeholderTester->getExpectByIDTest($idList[2])) && p()                                 && e('0');                 // 获取ID不存在的期望信息
