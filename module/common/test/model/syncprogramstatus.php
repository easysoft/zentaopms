#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->gen(20);

/**

title=测试 commonModel->syncProgramStatus();
timeout=0
cid=15721

- 执行$program1属性status @wait
- 执行$program2属性status @wait
- 执行$program3属性status @doing
- 将项目11的状态同步给父项目集属性status @doing
- 将项目12的状态同步给父项目集属性status @doing
- 将项目13的状态同步给父项目集属性status @doing

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');
$tester->loadModel('common');

$program1 = $tester->program->getById(1);
$program2 = $tester->program->getById(2);
$program3 = $tester->program->getById(3);

r($program1) && p('status') && e('wait');
r($program2) && p('status') && e('wait');
r($program3) && p('status') && e('doing');

$project11 = $tester->project->getById(11);
$project12 = $tester->project->getById(12);
$project13 = $tester->project->getById(13);

$tester->common->syncProgramStatus($project11);
$tester->common->syncProgramStatus($project12);
$tester->common->syncProgramStatus($project13);

$program1 = $tester->program->getById(1);
$program2 = $tester->program->getById(2);
$program3 = $tester->program->getById(3);

r($program1) && p('status') && e('doing'); // 将项目11的状态同步给父项目集
r($program2) && p('status') && e('doing'); // 将项目12的状态同步给父项目集
r($program3) && p('status') && e('doing'); // 将项目13的状态同步给父项目集