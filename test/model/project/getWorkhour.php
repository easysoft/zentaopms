#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getWorkhour;
cid=1
pid=1

预计61.0小时 >> 61.0
消耗95.0小时 >> 95.0

*/

$project = new Project('admin');

r($project->getWorkHour(13))   && p('totalEstimate') && e('61.0'); //预计61.0小时
r($project->getWorkHour(13))   && p('totalConsumed') && e('95.0'); //消耗95.0小时
r($project->getWorkHour(1000)) && p('totalEstimate') && e('');     //测试不存在的项目