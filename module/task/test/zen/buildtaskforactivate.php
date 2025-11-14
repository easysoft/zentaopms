#!/usr/bin/env php
<?php
/**

title=测试 taskZen::buildTaskForActivate();
timeout=0
cid=18906

- 测试剩余为负数属性left @预计剩余不能为负数
- 测试剩余为正数
 - 属性name @开发任务11
 - 属性status @closed
 - 属性pri @1
 - 属性estimate @0
 - 属性consumed @0
 - 属性left @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

$taskTable = zenData('task')->loadYaml('task');
$taskTable->status->range('closed');
$taskTable->gen(5);

zenData('user')->gen(5);
su('admin');

$leftList = array(-1, 1);

$taskTester = new taskZenTest();
r($taskTester->buildTaskForActivateTest(1, $leftList[0])) && p('left')                                   && e('预计剩余不能为负数');        // 测试剩余为负数
r($taskTester->buildTaskForActivateTest(1, $leftList[1])) && p('name,status,pri,estimate,consumed,left') && e('开发任务11,closed,1,0,0,0'); // 测试剩余为正数
