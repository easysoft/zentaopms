#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(50);
zenData('team')->loadYaml('team')->gen(500);
zenData('user')->gen(500);

/**

title=测试 projectModel->getTeamMembers();
timeout=0
cid=17856

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getTeamMembers(1)))   && p() && e('0');                     // 获取id为1的项目团队成员个数
r(count($tester->project->getTeamMembers(100))) && p() && e('0');                     // 获取id为100的项目团队成员个数
r(count($tester->project->getTeamMembers(11)))  && p() && e('5');                     // 获取id为11的项目团队成员个数
r($tester->project->getTeamMembers(11))         && p('user1:realname') && e('用户1'); // 获取id为11的项目团队成员的详细信息
r($tester->project->getTeamMembers(11))         && p('user2:realname') && e('用户2'); // 获取id为11的项目团队成员的详细信息
