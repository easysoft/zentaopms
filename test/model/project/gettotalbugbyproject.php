#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getTotalBugByProject;
cid=1
pid=1

状态一致时正常打印id为11的项目bug数量 >> 3

*/

$project = new Project('admin');

$getBug = array(11, 12, 13, 14, 15, 16, 27);

//var_dump($project->getTotalBugBy($getBug, 'active'));die;

r($project->getTotalBugBy($getBug, 'active')) && p(11) && e('3'); //状态一致时正常打印id为11的项目bug数量
r($project->getTotalBugBy($getBug, 'active')) && p(27) && e('');  //在状态status不一致的情况下，打印id为27的项目bug数量