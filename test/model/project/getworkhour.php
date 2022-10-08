#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getWorkhour;
cid=1
pid=1

获取项目ID为13的预计工时,61.0小时 >> 61.0
获取项目ID为13的消耗工时,95.0小时 >> 95.0

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getWorkHour(13))   && p('totalEstimate') && e('61.0'); //获取项目ID为13的预计工时,61.0小时
r($tester->project->getWorkHour(13))   && p('totalConsumed') && e('95.0'); //获取项目ID为13的消耗工时,95.0小时
r($tester->project->getWorkHour(1000)) && p('totalEstimate') && e('');     //测试不存在的项目