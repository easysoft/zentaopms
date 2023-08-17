#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::classifyProjects
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');

$projectsStats = $tester->program->getProjectStats(0, 'all', 0, 'order_asc');
$projects      = $tester->project->classifyProjects($projectsStats);

r(count($projects['myProjects']))     && p() && e('1'); // 测试分类后myProjects包含的项目数是3
r(count($projects['otherProjects']))  && p() && e('1'); // 测试分类后otherProjects包含的项目数是2
r(count($projects['closedProjects'])) && p() && e('1'); // 测试分类后closedProjects包含的项目数是1
