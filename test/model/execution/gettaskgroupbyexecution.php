#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试 executionModel->getTaskGroupByExecution();
cid=1
pid=1

测试空数据 >> 0
测试获取执行的任务 >> 3

*/


$executionIdList = array(0, 1, 101, 131, 161);

$execution = new executionTest();

r($execution->getTaskGroupByExecutionTest())                 && p() && e('0');  // 测试空数据
r($execution->getTaskGroupByExecutionTest($executionIdList)) && p() && e('3');  // 测试获取执行的任务
