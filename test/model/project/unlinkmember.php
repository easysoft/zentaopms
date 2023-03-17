#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->unlinkMember();
cid=1
pid=1

查看移除团队成员之前的ID为11的项目团队成员数量 >> 3
查看移除团队成员之后的ID为11的项目团队成员数量 >> 2
查看项目ID为11的团队成员的真实姓名 >> 产品经理92
查看项目ID为11的团队成员的真实姓名 >> 产品经理92

*/

global $tester;
$tester->loadModel('project');

$beforeMembers = $tester->project->getTeamMemberPairs(11);
$tester->project->unlinkMember(11, 'admin', false);
$afterMembers  = $tester->project->getTeamMemberPairs(11);

r(count($beforeMembers)) && p('')     && e('3');          // 查看移除团队成员之前的ID为11的项目团队成员数量
r(count($afterMembers))  && p('')     && e('2');          // 查看移除团队成员之后的ID为11的项目团队成员数量
r($beforeMembers)        && p('pm92') && e('产品经理92'); // 查看项目ID为11的团队成员的真实姓名
r($afterMembers)         && p('pm92') && e('产品经理92'); // 查看项目ID为11的团队成员的真实姓名
