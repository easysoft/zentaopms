#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getPairsByProgram();
cid=1
pid=1

查找管理员可查看的所有项目数量 >> 110
查找独立项目数量 >> 0
查找管理员可查看的所属项目集ID为1的项目数量 >> 9
查找管理员可查看的所属项目集ID为1且状态为wait的项目数量 >> 3
查找管理员可查看的所属项目集ID为1且状态不为closed的项目数量 >> 9

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getPairsByProgram('', 'all', true))) && p() && e('110'); // 查找管理员可查看的所有项目数量
r(count($tester->project->getPairsByProgram(0)))               && p() && e('0');   // 查找独立项目数量
r(count($tester->project->getPairsByProgram(1)))               && p() && e('9');   // 查找管理员可查看的所属项目集ID为1的项目数量
r(count($tester->project->getPairsByProgram(1, 'wait')))       && p() && e('3');   // 查找管理员可查看的所属项目集ID为1且状态为wait的项目数量
r(count($tester->project->getPairsByProgram(1, 'noclosed')))   && p() && e('9');   // 查找管理员可查看的所属项目集ID为1且状态不为closed的项目数量
