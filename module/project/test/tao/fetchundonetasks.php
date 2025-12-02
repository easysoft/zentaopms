#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(30);
zenData('task')->loadYaml('task')->gen(30);

/**

title=测试 projectModel->fetchUndoneTasks();
timeout=0
cid=17908

*/

$projectIdList = array(1, 11, 60, 100);

global $tester;
$tester->loadModel('project');

r(count($tester->project->fetchUndoneTasks($projectIdList[0]))) && p() && e('0');  // 测试获取不存在项目下的未完成的任务数量
r(count($tester->project->fetchUndoneTasks($projectIdList[1]))) && p() && e('12'); // 测试获取敏捷项目下的未完成的任务数量
r(count($tester->project->fetchUndoneTasks($projectIdList[2]))) && p() && e('8');  // 测试获取瀑布项目下的未完成的任务数量
r(count($tester->project->fetchUndoneTasks($projectIdList[3]))) && p() && e('0');  // 测试获取看板项目下的未完成的任务数量

r($tester->project->fetchUndoneTasks($projectIdList[0])) && p()               && e('0');        // 测试获取不存在项目下的未完成的任务信息
r($tester->project->fetchUndoneTasks($projectIdList[1])) && p('11:id,status') && e('27,doing'); // 测试获取敏捷项目下的未完成的任务信息
r($tester->project->fetchUndoneTasks($projectIdList[2])) && p('7:id,status')  && e('29,doing'); // 测试获取瀑布项目下的未完成的任务信息
r($tester->project->fetchUndoneTasks($projectIdList[3])) && p()               && e('0');        // 测试获取看板项目下的未完成的任务信息
