#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->gen(50);

$task = zdTable('task');
$task->project->range('1-20');
$task->gen(100);

/**

title=测试 programModel::computeProjectHours();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');
$tasks = $tester->program->dao->select('id, project, estimate, consumed, `left`, status, closedReason, execution')
    ->from(TABLE_TASK)
    ->where('project')->eq('11')
    ->andWhere('parent')->lt(1)
    ->andWhere('deleted')->eq(0)
    ->fetchGroup('project', 'id');

r($tester->program->computeProjectHours(array())) && p()                                                    && e('0');
r($tester->program->computeProjectHours($tasks))  && p('11:totalConsumed,totalEstimate,totalLeft,progress') && e('15,30,10,60');
