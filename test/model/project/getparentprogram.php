#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getParentName();
cid=1
pid=1

获取id为11的项目父项目名字 >> 项目集1
获取id为1的项目父项目名字 >> 0

*/

global $tester;
$tester->loadModel('project');

$project1 = $tester->project->getById(11);
$project2 = $tester->project->getById(1);

r($tester->project->getParentProgram($project1)) && p('name') && e('项目集1'); // 获取id为11的项目父项目名字
r($tester->project->getParentProgram($project2)) && p('name') && e('0');       // 获取id为1的项目父项目名字