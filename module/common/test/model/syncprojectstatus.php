#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(200);

/**

title=测试 commonModel->syncProjectStatus();
timeout=0
cid=15722

- 执行$execution1属性status @wait
- 执行$execution2属性status @doing
- 执行$execution3属性status @wait
- 将执行101的状态同步给父项目属性status @doing
- 将执行102的状态同步给父项目属性status @doing
- 将执行103的状态同步给父项目属性status @doing

*/

global $tester;
$tester->loadModel('project');
$tester->loadModel('common');

$execution1 = $tester->project->getById(101);
$execution2 = $tester->project->getById(102);
$execution3 = $tester->project->getById(103);

r($execution1) && p('status') && e('wait');
r($execution2) && p('status') && e('doing');
r($execution3) && p('status') && e('wait');

$project11 = $tester->project->getById(11);
$project12 = $tester->project->getById(12);
$project13 = $tester->project->getById(13);

$tester->common->syncProjectStatus($execution1);
$tester->common->syncProjectStatus($execution2);
$tester->common->syncProjectStatus($execution3);

$project11 = $tester->project->getById(11);
$project12 = $tester->project->getById(12);
$project13 = $tester->project->getById(13);

r($project11) && p('status') && e('doing'); // 将执行101的状态同步给父项目
r($project12) && p('status') && e('doing'); // 将执行102的状态同步给父项目
r($project13) && p('status') && e('doing'); // 将执行103的状态同步给父项目