#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->processProjectActions();
cid=1
pid=1

测试计算action 1 2 3 4 5 的项目动态 >> 5
测试计算action 26 27 28 29 30 的项目动态 >> 28
测试计算action 51 52 53 54 55 的项目动态 >> 51
测试计算action 71 72 73 74 75 的项目动态 >> 74
测试计算action 96 97 98 99 100 的项目动态 >> 97
测试计算action 5 28 51 74 97 的项目动态 >> 5,28,51,74,97

*/

$actions = array('1,2,3,4,5', '26,27,28,29,30', '51,52,53,54,55', '71,72,73,74,75', '96,97,98,99,100', '5,28,51,74,97');

$action = new actionTest();

r($action->processProjectActionsTest($actions[0])) && p() && e('5');             // 测试计算action 1 2 3 4 5 的项目动态
r($action->processProjectActionsTest($actions[1])) && p() && e('28');            // 测试计算action 26 27 28 29 30 的项目动态
r($action->processProjectActionsTest($actions[2])) && p() && e('51');            // 测试计算action 51 52 53 54 55 的项目动态
r($action->processProjectActionsTest($actions[3])) && p() && e('74');            // 测试计算action 71 72 73 74 75 的项目动态
r($action->processProjectActionsTest($actions[4])) && p() && e('97');            // 测试计算action 96 97 98 99 100 的项目动态
r($action->processProjectActionsTest($actions[5])) && p() && e('5,28,51,74,97'); // 测试计算action 5 28 51 74 97 的项目动态