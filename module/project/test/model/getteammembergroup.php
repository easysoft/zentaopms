#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(50);
zenData('team')->loadYaml('team')->gen(500);
zenData('user')->gen(500);

/**

title=测试 projectModel->getTeamMemberGroup();
timeout=0
cid=17854

- 通过ID列表：11,12,13,14,15获取项目团队成员分组 @1

- 通过ID列表：100,101,102,103获取项目团队成员分组 @1

- 通过ID列表：60,61获取项目团队成员分组 @2

- 获取id为100的项目团队成员的详细信息第test1条的realname属性 @测试1

- 获取id为60的项目团队成员的详细信息第user10条的realname属性 @用户10

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getTeamMemberGroup(array(11, 12, 13, 14, 15)))) && p() && e('1'); // 通过ID列表：11,12,13,14,15获取项目团队成员分组
r(count($tester->project->getTeamMemberGroup('100,101,102,103')))         && p() && e('1'); // 通过ID列表：100,101,102,103获取项目团队成员分组
r(count($tester->project->getTeamMemberGroup(array(60,61))))              && p() && e('2'); // 通过ID列表：60,61获取项目团队成员分组
r($tester->project->getTeamMemberGroup('100')[100])                       && p('test1:realname')  && e('测试1');  // 获取id为100的项目团队成员的详细信息
r($tester->project->getTeamMemberGroup('60')[60])                         && p('user10:realname') && e('用户10'); // 获取id为60的项目团队成员的详细信息