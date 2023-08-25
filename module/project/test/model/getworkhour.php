#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('execution')->gen(10);
zdTable('task')->config('task')->gen(30);
zdTable('team')->config('team')->gen(10);

/**

title=测试 projectModel::getWorkhour;
timeout=0
cid=1

*/

$projectIdList = array(1, 11, 60);

global $tester;
$tester->loadModel('project');

r($tester->project->getWorkHour($projectIdList[0])) && p('totalHours')    && e('0');  // 获取不存在项目工时信息
r($tester->project->getWorkHour($projectIdList[1])) && p('totalEstimate') && e('61'); // 获取项目ID为11的总预计工时
r($tester->project->getWorkHour($projectIdList[2])) && p('totalConsumed') && e('11'); // 获取项目ID为60的总消耗工时
