#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::sortAndReduceClosedGroup();
timeout=0
cid=1

- 执行$projectsStats['my'][3]['closed'] @1

- 执行$projectsStats['other'][4]['closed'] @1

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');

$projectsStats = $tester->program->getProjectStats(0, 'all', 0, 'order_asc');
$projectsStats = $tester->project->classfyProjects($projectsStats);
$projectsStats = $tester->project->sortAndReduceClosedGroup($projectsStats, 2);

r(count($projectsStats['my'][3]['closed']))    && p() && e('1');
r(count($projectsStats['other'][4]['closed'])) && p() && e('1');