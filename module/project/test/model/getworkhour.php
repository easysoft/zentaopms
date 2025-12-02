#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(10);
zenData('task')->loadYaml('task')->gen(30);
zenData('team')->loadYaml('team')->gen(10);

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
 - 属性totalHours @112.5
 - 属性totalEstimate @61
 - 属性totalConsumed @18
 - 属性totalLeft @61
- 获取不存在项目工时信息
 - 属性totalHours @0
 - 属性totalEstimate @0
 - 属性totalConsumed @0
 - 属性totalLeft @0
- 获取项目ID为60的总消耗工时
 - 属性totalHours @300
 - 属性totalEstimate @35
 - 属性totalConsumed @11
 - 属性totalLeft @35
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
r($tester->project->getWorkHour($projectIdList[1])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('112.5,61,18,61'); // 获取项目ID为11的总预计工时
r($tester->project->getWorkHour($projectIdList[2])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,0,0,0');        // 获取不存在项目工时信息
r($tester->project->getWorkHour($projectIdList[3])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('300,35,11,35');   // 获取项目ID为60的总消耗工时
r($tester->project->getWorkHour($projectIdList[4])) && p('totalHours,totalEstimate,totalConsumed,totalLeft') && e('0,0,0,0');        // 获取不存在项目工时信息
