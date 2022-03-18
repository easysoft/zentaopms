#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 programModel::saveState;
cid=1
pid=1

传入存在ID的值14，保存session，返回对应值14 >> 14
传入存在ID的值99，保存session，返回第一位默认值11 >> 11

*/

$project = new Project('dev1');

$projectId = array(14, 99);

r($project->saveState($projectId[0])) && p() && e('14'); //传入存在ID的值14，保存session，返回对应值14
r($project->saveState($projectId[1])) && p() && e('11'); //传入存在ID的值99，保存session，返回第一位默认值11