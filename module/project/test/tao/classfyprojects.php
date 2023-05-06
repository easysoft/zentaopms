#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('1')->gen(8);

/**

title=测试 projectTao::classfyProjects
timeout=0
cid=1

- 执行$executions['myProjects'] @3

- 执行$executions['otherProjects'] @2

- 执行$executions['closedGroup'] @1

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');

$projectsStats = $tester->program->getProjectStats(0, 'all', 0, 'order_asc');
$executions    = $tester->project->classfyProjects($projectsStats);

r(count($executions['myProjects']))    && p() && e('3');
r(count($executions['otherProjects'])) && p() && e('2');
r(count($executions['closedGroup']))   && p() && e('1');