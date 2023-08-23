#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(50);
zdTable('team')->config('team')->gen(500);
zdTable('user')->gen(500);

/**

title=测试 projectModel->getTeamMemberPairs();
timeout=0
cid=1

- 获取id为1的项目团队成员个数 @0

- 获取id为100的项目团队成员个数 @0

- 获取id为11的项目团队成员个数 @5

- 获取id为11的项目团队成员的详细信息
 - 属性user1 @用户1
 - 属性user2 @用户2

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getTeamMemberPairs(1)))   && p() && e('0'); // 获取id为1的项目团队成员个数
r(count($tester->project->getTeamMemberPairs(100))) && p() && e('0'); // 获取id为100的项目团队成员个数
r(count($tester->project->getTeamMemberPairs(11)))  && p() && e('5'); // 获取id为11的项目团队成员个数
r($tester->project->getTeamMemberPairs(11))         && p('user1,user2') && e('用户1,用户2'); // 获取id为11的项目团队成员的详细信息