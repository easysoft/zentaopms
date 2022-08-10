#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::checkHasChildren();
cid=1
pid=1

获取id为1的项目是否有子项目 >> 1
获取id为101的项目是否有子项目 >> 0

*/

$project = new Project('admin');

$getID = array(1, 101);

r($project->checkHasChildren($getID[0])) && p() && e('1'); //获取id为1的项目是否有子项目
r($project->checkHasChildren($getID[1])) && p() && e('0'); //获取id为101的项目是否有子项目