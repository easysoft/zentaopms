#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->computerProgress();
cid=1
pid=1

查看项目11下的所有执行的数量 >> 7
查看计算后的执行工时汇总信息 >> 10,3,10,23.08,13
查看计算后的执行工时汇总信息 >> 1,3,1,75,4

*/

global $tester;
$tester->loadModel('project');
$tester->loadModel('execution');

$executions = $tester->execution->getList(11);
$executions = $tester->project->computerProgress($executions);

r(count($executions)) && p('')                                                             && e('7');                // 查看项目11下的所有执行的数量
r($executions)        && p('551:totalEstimate,totalConsumed,totalLeft,progress,totalReal') && e('10,3,10,23.08,13'); // 查看计算后的执行工时汇总信息
r($executions)        && p('641:totalEstimate,totalConsumed,totalLeft,progress,totalReal') && e('1,3,1,75,4');       // 查看计算后的执行工时汇总信息