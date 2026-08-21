#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(10);
zenData('task')->loadYaml('task')->gen(30);
$team = zenData('team');
$team->id->range('1-10');
$team->root->range('1-10');
$team->type->range('project');
$team->account->range('admin');
$team->days->range('0');
$team->hours->range('0');
$team->gen(10);

/**

title=测试 projectModel::getWorkhour;
timeout=0
cid=17859

- 获取不存在项目工时信息
 - 属性totalHours @0
 - 属性totalEstimate @0
 - 属性totalConsumed @0
 - 属性totalLeft @0
- 获取项目ID为11的总预计工时
 - 属性totalHours @0
 - 属性totalEstimate @61.0
 - 属性totalConsumed @18.0
 - 属性totalLeft @61.0
- 获取不存在项目工时信息
 - 属性totalHours @0
 - 属性totalEstimate @0
 - 属性totalConsumed @0
 - 属性totalLeft @0
- 获取项目ID为60的总消耗工时
 - 属性totalHours @0
 - 属性totalEstimate @35.0
 - 属性totalConsumed @11.0
 - 属性totalLeft @35.0
- 获取不存在项目工时信息
 - 属性totalHours @0
 - 属性totalEstimate @0
 - 属性totalConsumed @0
 - 属性totalLeft @0

*/

$projectIdList = array(1, 11, 21, 60, 61);

global $tester;
$tester->loadModel('project');

r($tester->project->getWorkHour($projectIdList[0])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,0,0,0');        // 获取不存在项目工时信息
r($tester->project->getWorkHour($projectIdList[1])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,61.0,18.0,61.0'); // 获取项目ID为11的总预计工时
r($tester->project->getWorkHour($projectIdList[2])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,0,0,0');        // 获取不存在项目工时信息
r($tester->project->getWorkHour($projectIdList[3])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,35.0,11.0,35.0'); // 获取项目ID为60的总消耗工时
r($tester->project->getWorkHour($projectIdList[4])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,0,0,0');        // 获取不存在项目工时信息