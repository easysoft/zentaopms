#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->deleteExpect();
cid=1

- 删除ID=0的期望 @0
- 删除ID=1的期望属性deleted @1
- 删除ID不存在的期望 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('expect')->gen(1);
zdTable('user')->gen(5);

$expectIds = array(0, 1, 2);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->deleteExpectTest($expectIds[0])) && p()          && e('0'); // 删除ID=0的期望
r($stakeholderTester->deleteExpectTest($expectIds[1])) && p('deleted') && e('1'); // 删除ID=1的期望
r($stakeholderTester->deleteExpectTest($expectIds[2])) && p()          && e('0'); // 删除ID不存在的期望
