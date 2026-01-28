#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$taskTable = zenData('task')->loadYaml('task');
$taskTable->assignedTo->range('admin,user1,user2,user3');
$taskTable ->gen('30');
zenData('project')->loadYaml('execution')->gen('130');
zenData('user')->gen(10);

su('admin');

/**

title=测试 reportModel->getUserTasks();
timeout=0
cid=18170

- 获取admin的任务ID第0条的id属性 @25
- 获取user1的任务ID第0条的id属性 @26
- 获取user2的任务ID第0条的id属性 @7
- 获取admin的任务数 @1
- 获取user1的任务数 @1
- 获取user2的任务数 @2

*/

$report = new reportModelTest();
$result = $report->getUserTasksTest();
r($result['admin'])        && p('0:id') && e('25'); // 获取admin的任务ID
r($result['user1'])        && p('0:id') && e('26'); // 获取user1的任务ID
r($result['user2'])        && p('0:id') && e('7');  // 获取user2的任务ID
r(count($result['admin'])) && p()       && e('1');  // 获取admin的任务数
r(count($result['user1'])) && p()       && e('1');  // 获取user1的任务数
r(count($result['user2'])) && p()       && e('2');  // 获取user2的任务数