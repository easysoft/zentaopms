#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zenData('project')->loadYaml('project')->gen(8);

/**

title=测试 projectTao::sortAndReduceClosedProjects();
timeout=0
cid=17919

- 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目包含的个数为1 @1
- 测试$projectsStats['my'][0]中排序和缩减后的已关闭的项目包含的个数为1 @1
- 测试$projectsStats['other'][0]中排序和缩减后的进行中的项目包含的个数为1 @1
- 测试$projectsStats['other'][0]中排序和缩减后的已关闭的项目包含的个数为1 @1
- 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目的第一项
 - 属性id @1
 - 属性type @project
 - 属性name @项目集1
- 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目的第一项
 - 属性id @2
 - 属性type @project
 - 属性name @项目集2

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');

$projectsStats = $tester->program->getProjectStats(0, 'all', 0, 'order_asc');
$projectsStats = $tester->project->classifyProjects($projectsStats);
$projectsStats = $tester->project->sortAndReduceClosedProjects($projectsStats, 2);

r(count($projectsStats['my'][0]['doing']))     && p() && e('1'); // 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目包含的个数为1
r(count($projectsStats['my'][0]['closed']))    && p() && e('1'); // 测试$projectsStats['my'][0]中排序和缩减后的已关闭的项目包含的个数为1
r(count($projectsStats['other'][0]['doing']))  && p() && e('1'); // 测试$projectsStats['other'][0]中排序和缩减后的进行中的项目包含的个数为1
r(count($projectsStats['other'][0]['closed'])) && p() && e('1'); // 测试$projectsStats['other'][0]中排序和缩减后的已关闭的项目包含的个数为1

r($projectsStats['my'][0]['doing'][0])    && p('id,type,name') && e('1,project,项目集1'); // 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目的第一项
r($projectsStats['other'][0]['doing'][0]) && p('id,type,name') && e('2,project,项目集2'); // 测试$projectsStats['my'][0]中排序和缩减后的进行中的项目的第一项