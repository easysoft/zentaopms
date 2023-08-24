#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('execution')->gen(10);
zdTable('task')->config('task')->gen(30);
zdTable('effort')->config('effort')->gen(30);

/**

title=测试 projectModel::getProjectsConsumed;
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$projectIdList[0] = array(1, 2);
$projectIdList[1] = array(11, 60);
$projectIdList[2] = array(61, 100);

r($tester->project->getProjectsConsumed($projectIdList[0])) && p('1:totalConsumed')  && e('0');  // 批量获取不存在项目的总计消耗工时
r($tester->project->getProjectsConsumed($projectIdList[1])) && p('11:totalConsumed') && e('55'); // 批量获取有工时记录的项目的总计消耗工时
r($tester->project->getProjectsConsumed($projectIdList[2])) && p('61:totalConsumed') && e('0');  // 批量获取没有工时记录项目的总计消耗工时

r($tester->project->getProjectsConsumed($projectIdList[0], 'THIS_YEAR')) && p('1:totalConsumed')  && e('0');  // 批量获取今年不存在项目的总计消耗工时
r($tester->project->getProjectsConsumed($projectIdList[1], 'THIS_YEAR')) && p('11:totalConsumed') && e('55'); // 批量获取今年有工时记录的项目的总计消耗工时
r($tester->project->getProjectsConsumed($projectIdList[2], 'THIS_YEAR')) && p('61:totalConsumed') && e('0');  // 批量获取今年没有工时记录项目的总计消耗工时
