#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 programModel::saveState;
cid=1
pid=1

传入存在ID的值99，保存session，返回session >> 99
不传入ID，保存session，返回第一个key >> 20

*/

global $tester;
$tester->loadModel('project');

$projects = array(20 => 20, 21 => 21, 22 => 22);

r($tester->project->saveState(99))           && p() && e('99'); //传入存在ID的值99，保存session，返回session
r($tester->project->saveState(0, $projects)) && p() && e('20'); //不传入ID，保存session，返回第一个key
