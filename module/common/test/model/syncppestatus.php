#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('execution')->gen(200);
zdTable('task')->gen(10);

/**

title=测试 commonModel->syncPPEStatus();
timeout=0
cid=1

- 执行$project1属性status @wait
- 执行$project2属性status @doing
- 执行$project3属性status @wait
- 同步后，查看项目状态属性status @doing
- 同步后，查看项目状态属性status @doing
- 同步后，查看项目状态属性status @doing
- 执行$program1属性status @wait
- 执行$program2属性status @doing
- 执行$program3属性status @wait
- 同步后，查看项目集状态属性status @doing
- 同步后，查看项目集状态属性status @doing
- 同步后，查看项目集状态属性status @doing

*/

global $tester, $app;
$tester->loadModel('execution');
$tester->loadModel('common');

$app->rawModule = 'execution';
$app->rawMethod = 'task';

$project1 = $tester->execution->getById(11); // 同步前，查看项目状态
$project2 = $tester->execution->getById(12); // 同步前，查看项目状态
$project3 = $tester->execution->getById(13); // 同步前，查看项目状态

r($project1) && p('status') && e('wait');
r($project2) && p('status') && e('doing');
r($project3) && p('status') && e('wait');

$tester->common->syncPPEStatus(101);
$tester->common->syncPPEStatus(102);
$tester->common->syncPPEStatus(103);

$project1 = $tester->execution->getById(11);
$project2 = $tester->execution->getById(12);
$project3 = $tester->execution->getById(13);

r($project1) && p('status') && e('doing'); // 同步后，查看项目状态
r($project2) && p('status') && e('doing'); // 同步后，查看项目状态
r($project3) && p('status') && e('doing'); // 同步后，查看项目状态

$app->rawModule = 'project';
$app->rawMethod = 'all';

$program1 = $tester->execution->getById(5); // 同步前，查看项目集状态
$program2 = $tester->execution->getById(6); // 同步前，查看项目集状态
$program3 = $tester->execution->getById(7); // 同步前，查看项目集状态

r($program1) && p('status') && e('wait');
r($program2) && p('status') && e('doing');
r($program3) && p('status') && e('wait');

$tester->common->syncPPEStatus(15);
$tester->common->syncPPEStatus(16);
$tester->common->syncPPEStatus(17);

$program1 = $tester->execution->getById(5);
$program2 = $tester->execution->getById(6);
$program3 = $tester->execution->getById(7);

r($program1) && p('status') && e('doing'); // 同步后，查看项目集状态
r($program2) && p('status') && e('doing'); // 同步后，查看项目集状态
r($program3) && p('status') && e('doing'); // 同步后，查看项目集状态