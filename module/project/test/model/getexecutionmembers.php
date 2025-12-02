#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getExecutionMembers();
timeout=0
cid=17825

- 获取admin的执行键值对总数 @2
- 获取admin的执行键值对 @迭代1-2
- 获取admin的执行键值对 @迭代6-7
- 获取user1的执行键值对总数 @1
- 获取user1的执行键值对 @迭代2-4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(7);
zenData('team')->loadYaml('team')->gen(4);
zenData('user')->loadYaml('user')->gen(3);

global $tester;
$tester->loadModel('project');

$executionIdList = array(2, 4, 5, 7);
$adminExecutions = $tester->project->getExecutionMembers('admin', $executionIdList);
$user1Executions = $tester->project->getExecutionMembers('user1', $executionIdList);

r(count($adminExecutions)) && p() && e('2');       // 获取admin的执行键值对总数
r($adminExecutions[2])     && p() && e('迭代1-2'); // 获取admin的执行键值对
r($adminExecutions[7])     && p() && e('迭代6-7'); // 获取admin的执行键值对

r(count($user1Executions)) && p() && e('1');       // 获取user1的执行键值对总数
r($user1Executions[4])     && p() && e('迭代2-4'); // 获取user1的执行键值对
