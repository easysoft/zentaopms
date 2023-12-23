#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('project')->gen(50);
su('admin');

/**

title=测试 projectModel->getPairsByProgram();
timeout=0
cid=1

- 查找管理员可查看的所有项目数量 @4

- 查找独立项目数量 @40

- 查找管理员可查看的所属项目集ID为1的项目数量 @4

- 查找管理员可查看的所属项目集ID为1且状态为wait的项目数量 @1

- 查找管理员可查看的所属项目集ID为1且状态不为closed的项目数量 @4

*/

global $tester;
$tester->loadModel('project');
r(count($tester->project->getPairsByProgram(10, 'all', true))) && p() && e('4');  // 查找管理员可查看的所有项目数量
r(count($tester->project->getPairsByProgram()))                && p() && e('40'); // 查找独立项目数量
r(count($tester->project->getPairsByProgram(1)))               && p() && e('4');  // 查找管理员可查看的所属项目集ID为1的项目数量
r(count($tester->project->getPairsByProgram(1, 'wait')))       && p() && e('1');  // 查找管理员可查看的所属项目集ID为1且状态为wait的项目数量
r(count($tester->project->getPairsByProgram(1, 'noclosed')))   && p() && e('4');  // 查找管理员可查看的所属项目集ID为1且状态不为closed的项目数量
