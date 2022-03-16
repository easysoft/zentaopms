#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getParentName();
cid=1
pid=1

获取id为11的项目父项目名字 >> 项目集1
获取id为1的项目父项目名字 >> 项目集1
获取id为0的项目父项目名字 >> 0

*/

$project = new Project('admin');

$getName = array(11, 1, 0);

r($project->getParentName($getName[0]))  && p('name') && e('项目集1'); //获取id为11的项目父项目名字
r($project->getParentName($getName[1]))  && p('name') && e('项目集1'); //获取id为1的项目父项目名字
r($project->getParentName($getName[2]))  && p('name') && e('0');       //获取id为0的项目父项目名字