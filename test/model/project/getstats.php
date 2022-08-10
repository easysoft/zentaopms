#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getStats();
cid=1
pid=1

查看所有执行数量 >> 630
查看未完成的执行数量 >> 630
查看所有进行中的执行数量 >> 315
查看所有进行中的执行数量 >> 315
查看id为11项目的执行数量 >> 7
查看id为12项目的执行数量 >> 7

*/

global $tester;
$tester->loadModel('project');

$OpenImplement = array('all', 'undone', 'doing', 'wait', '11', '12');

$allExecutions       = $tester->project->getStats(0, 'all');
$undoneExecutions    = $tester->project->getStats(0, 'undone');
$doingExecutions     = $tester->project->getStats(0, 'doing');
$waitExecutions      = $tester->project->getStats(0, 'wait');
$project11Executions = $tester->project->getStats(11);
$project12Executions = $tester->project->getStats(12);

r(count($allExecutions))         && p() && e('630'); // 查看所有执行数量
r(count($undoneExecutions))      && p() && e('630'); // 查看未完成的执行数量
r(count($doingExecutions))       && p() && e('315'); // 查看所有进行中的执行数量
r(count($waitExecutions))        && p() && e('315'); // 查看所有进行中的执行数量
r(count($project11Executions))   && p() && e('7');   // 查看id为11项目的执行数量
r(count($project12Executions))   && p() && e('7');   // 查看id为12项目的执行数量