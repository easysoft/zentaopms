#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getProjectsConsumed;
cid=1
pid=1

批量获取各个项目的总计消耗工时 >> 0
批量获取各个项目的总计消耗工时 >> 0
批量获取各个项目的总计消耗工时 >> 0

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13);

r($tester->project->getProjectsConsumed($projectIdList)) && p('11:totalConsumed') && e('0'); // 批量获取各个项目的总计消耗工时
r($tester->project->getProjectsConsumed($projectIdList)) && p('12:totalConsumed') && e('0'); // 批量获取各个项目的总计消耗工时
r($tester->project->getProjectsConsumed($projectIdList)) && p('13:totalConsumed') && e('0'); // 批量获取各个项目的总计消耗工时
