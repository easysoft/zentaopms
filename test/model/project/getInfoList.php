#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getInfoList;
cid=1
pid=1



*/

$project = $tester->loadModel('project');

$undoneProject = $project->getInfoList('wait');
var_dump($undoneProject);
