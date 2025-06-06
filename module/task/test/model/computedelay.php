#!/usr/bin/env php
<?php

/**

title=taskModel->computeDelay();
timeout=0
cid=0

- 测试获取有截止日期进行中的任务延期天数属性delay @1
- 测试获取有截止日期已完成的任务延期天数属性delay @~~
- 测试获取没有截止日期未开始的任务延期天数属性delay @~~
- 测试获取有截止日期已取消的任务延期天数属性delay @~~
- 测试获取有截止日期已关闭的任务延期天数属性delay @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('task')->loadYaml('task')->gen(5);

$taskIdList = range(1, 5);
$taskTester = new taskTest();
r($taskTester->computeDelayTest($taskIdList[1], true))  && p('delay') && e('1');  // 测试获取有截止日期进行中的任务延期天数
r($taskTester->computeDelayTest($taskIdList[2], true))  && p('delay') && e('~~'); // 测试获取有截止日期已完成的任务延期天数
r($taskTester->computeDelayTest($taskIdList[0], false)) && p('delay') && e('~~'); // 测试获取没有截止日期未开始的任务延期天数
r($taskTester->computeDelayTest($taskIdList[3], false)) && p('delay') && e('~~'); // 测试获取有截止日期已取消的任务延期天数
r($taskTester->computeDelayTest($taskIdList[4], false)) && p('delay') && e('~~'); // 测试获取有截止日期已关闭的任务延期天数