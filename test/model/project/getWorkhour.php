#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getWorkhour;
cid=1
pid=1



*/

$project = new Project('admin');

var_dump($project->getWorkHour(1));die;