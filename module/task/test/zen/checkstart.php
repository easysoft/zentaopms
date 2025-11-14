#!/usr/bin/env php
<?php
/**

title=测试 taskZen::checkStart();
timeout=0
cid=18926

- 测试总消耗小于之前的消耗属性consumed @"总计消耗"必须大于之前消耗
- 测试开始一个进行中的任务 @此任务已被启动，不能重复启动！
- 测试消耗跟剩余都为0属性message @"总计消耗"和"预计剩余"不能同时为0
- 测试剩余为负数属性left @预计剩余不能为负数
- 测试多人任务，总消耗小于之前的消耗属性consumed @"总计消耗"必须大于之前消耗
- 测试开始一个进行中的多人任务 @此任务已被启动，不能重复启动！

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

$taskTable = zenData('task')->loadYaml('task');
$taskTable->consumed->range('3');
$taskTable->status->range('wait,doing{9}');
$taskTable->gen(10);

$taskteamTable = zenData('taskteam')->loadYaml('taskteam');
$taskteamTable->consumed->range('2');
$taskteamTable->status->range('doing');
$taskteamTable->gen(10);

zenData('user')->gen(5);
su('admin');

$taskIdList   = array(1, 2, 3, 4, 8, 9);
$consumedList = array(0, 2, 3);
$leftList     = array(0, -1);

$taskTester = new taskZenTest();
r($taskTester->checkStartTest($taskIdList[0], $consumedList[1], $leftList[0])) && p('consumed') && e('"总计消耗"必须大于之前消耗');        // 测试总消耗小于之前的消耗
r($taskTester->checkStartTest($taskIdList[1], $consumedList[1], $leftList[0])) && p('0')        && e('此任务已被启动，不能重复启动！');    // 测试开始一个进行中的任务
r($taskTester->checkStartTest($taskIdList[2], $consumedList[0], $leftList[0])) && p('message')  && e('"总计消耗"和"预计剩余"不能同时为0'); // 测试消耗跟剩余都为0
r($taskTester->checkStartTest($taskIdList[3], $consumedList[1], $leftList[1])) && p('left')     && e('预计剩余不能为负数');                // 测试剩余为负数
r($taskTester->checkStartTest($taskIdList[4], $consumedList[0], $leftList[0])) && p('consumed') && e('"总计消耗"必须大于之前消耗');        // 测试多人任务，总消耗小于之前的消耗
r($taskTester->checkStartTest($taskIdList[5], $consumedList[0], $leftList[0])) && p('0')        && e('此任务已被启动，不能重复启动！');    // 测试开始一个进行中的多人任务
