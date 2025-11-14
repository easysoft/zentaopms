#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(200);
zenData('task')->gen(10);

/**

title=测试 commonModel->syncExecutionStatus();
timeout=0
cid=15719

- 执行$execution1属性status @wait
- 执行$execution2属性status @doing
- 执行$execution3属性status @wait
- 任务开始将执行的状态设为doing属性status @doing
- 任务开始将执行的状态设为doing属性status @doing
- 任务开始将执行的状态设为doing属性status @doing

*/

global $tester;
$tester->loadModel('execution');
$tester->loadModel('common');

$execution1 = $tester->execution->getById(101);
$execution2 = $tester->execution->getById(102);
$execution3 = $tester->execution->getById(103);

r($execution1) && p('status') && e('wait');
r($execution2) && p('status') && e('doing');
r($execution3) && p('status') && e('wait');

$tester->common->syncExecutionStatus(1);
$tester->common->syncExecutionStatus(2);
$tester->common->syncExecutionStatus(3);

$execution1 = $tester->execution->getById(101);
$execution2 = $tester->execution->getById(102);
$execution3 = $tester->execution->getById(103);

r($execution1) && p('status') && e('doing'); // 任务开始将执行的状态设为doing
r($execution2) && p('status') && e('doing'); // 任务开始将执行的状态设为doing
r($execution3) && p('status') && e('doing'); // 任务开始将执行的状态设为doing