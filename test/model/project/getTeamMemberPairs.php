#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getTeamMembers();
cid=1
pid=1

获取id为11的项目团队成员个数 >> 2
获取id为1的项目团队成员个数 >> 0

*/

$project = new Project('admin');

$getIdNub = array(11, 1);

r($project->getTeamMemberPairs($getIdNub[0])) && p() && e('2'); //获取id为11的项目团队成员个数
r($project->getTeamMemberPairs($getIdNub[1]))  && p() && e('0'); //获取id为1的项目团队成员个数