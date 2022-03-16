#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getTeamMembers();
cid=1
pid=1

获取id为11的项目团队成员个数 >> 2
获取id为11的项目团队成员个数，开启新手引导 >> 1

*/

$project = new Project('admin');

$getNu = array(11, array('admin', 'pm92'), 11, array('admin'), true);

r($project->getTeamMembers($getNu[0], $getNu[1]))              && p() && e('2'); //获取id为11的项目团队成员个数
r($project->getTeamMembers($getNu[2], $getNu[3], $getNu[4]))   && p() && e('1'); //获取id为11的项目团队成员个数，开启新手引导