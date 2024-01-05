#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(10);

/**

title=测试 actionModel->processProjectActions();
timeout=0
cid=1

- 测试计算action 1 2 3 4 5      的项目动态 >> 0 @0
- 测试计算action 6 7 8 9 10     的项目动态 >> 5 @7,8,9,10

- 测试计算action 11 12 13 14 15 的项目动态 >> 0 @0

*/

$actions = array('1,2,3,4,5', '6,7,8,9,10', '11,12,13,14,15');

$action = new actionTest();

r($action->processProjectActionsTest($actions[0])) && p() && e('0');             // 测试计算action 1 2 3 4 5      的项目动态 >> 0
r($action->processProjectActionsTest($actions[1])) && p() && e('7,8,9,10');      // 测试计算action 6 7 8 9 10     的项目动态 >> 5
r($action->processProjectActionsTest($actions[2])) && p() && e('0');             // 测试计算action 11 12 13 14 15 的项目动态 >> 0