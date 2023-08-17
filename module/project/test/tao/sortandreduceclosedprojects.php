#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::sortAndReduceClosedProjects();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');

$projectsStats = $tester->program->getProjectStats(0, 'all', 0, 'order_asc');
$projectsStats = $tester->project->classifyProjects($projectsStats);
$projectsStats = $tester->project->sortAndReduceClosedProjects($projectsStats, 2);

r(count($projectsStats['my'][0]['doing']))    && p() && e('1'); // 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目包含的个数为1
r(count($projectsStats['other'][0]['doing'])) && p() && e('1'); // 测试$projectsStats['other'][0]中排序和缩减后的进行中的项目包含的个数为1
