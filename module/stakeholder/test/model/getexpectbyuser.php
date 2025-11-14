#!/usr/bin/env php
<?php

/**

title=测试 stakeholderModel->getExpectByUser();
timeout=0
cid=18433

- 获取userID为0的用户的期望 @0
- 获取userID为11的用户的期望
 - 第1条的userID属性 @11
 - 第1条的expect属性 @期望1
 - 第1条的progress属性 @进度1
 - 第1条的project属性 @11
- 获取userID不存在的用户的期望 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stakeholder.unittest.class.php';

zenData('user')->gen(5);
zenData('expect')->loadYaml('expect')->gen(1);

$userIds = array(0, 11, 12);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getExpectByUserTest($userIds[0])) && p()                                   && e('0');                 // 获取userID为0的用户的期望
r($stakeholderTester->getExpectByUserTest($userIds[1])) && p('1:userID,expect,progress,project') && e('11,期望1,进度1,11'); // 获取userID为11的用户的期望
r($stakeholderTester->getExpectByUserTest($userIds[2])) && p()                                   && e('0');                 // 获取userID不存在的用户的期望