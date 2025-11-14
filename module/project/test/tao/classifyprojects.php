#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zenData('project')->loadYaml('project')->gen(8);

/**

title=测试 projectTao::classifyProjects
timeout=0
cid=17891

- 测试分类后myProjects包含的项目数是3 @1
- 测试分类后otherProjects包含的项目数是2 @1
- 测试分类后closedProjects包含的项目数是1 @1
- 测试分类后 myProjects 项目 wait 的第一个项目
 - 属性id @1
 - 属性name @项目集1
- 测试分类后 myProjects 项目 doing 的第一个项目
 - 属性id @3
 - 属性name @项目集3
- 测试分类后 otherProjects 项目 wait 的第一个项目
 - 属性id @2
 - 属性name @项目集2
- 测试分类后 otherProjects 项目 doing 的第一个项目
 - 属性id @4
 - 属性name @项目集4

*/

global $tester;
$tester->loadModel('program');
$tester->loadModel('project');

$projectsStats = $tester->program->getProjectStats(0, 'all', 0, 'order_asc');
$projects      = $tester->project->classifyProjects($projectsStats);

r(count($projects['myProjects']))            && p()          && e('1');         // 测试分类后myProjects包含的项目数是3
r(count($projects['otherProjects']))         && p()          && e('1');         // 测试分类后otherProjects包含的项目数是2
r(count($projects['closedProjects']))        && p()          && e('1');         // 测试分类后closedProjects包含的项目数是1
r($projects['myProjects'][0]['wait'][0])     && p('id,name') && e('1,项目集1');  // 测试分类后 myProjects 项目 wait 的第一个项目
r($projects['myProjects'][0]['doing'][0])    && p('id,name') && e('3,项目集3');  // 测试分类后 myProjects 项目 doing 的第一个项目
r($projects['otherProjects'][0]['wait'][0])  && p('id,name') && e('2,项目集2');  // 测试分类后 otherProjects 项目 wait 的第一个项目
r($projects['otherProjects'][0]['doing'][0]) && p('id,name') && e('4,项目集4');  // 测试分类后 otherProjects 项目 doing 的第一个项目
