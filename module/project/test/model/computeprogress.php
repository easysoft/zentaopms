#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('execution')->gen(10);
zdTable('task')->config('task')->gen(20);
zdTable('taskteam')->gen(20);
su('admin');

/**

title=测试 projectModel->computeProgress();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');
$tester->loadModel('execution');

$executions = $tester->execution->getList(11);
$executions = $tester->project->computeProgress($executions);

r(count($executions)) && p('')                                                             && e('1');                // 查看项目11下的所有执行的数量
r($executions)        && p('101:totalEstimate,totalConsumed,totalLeft,progress,totalReal') && e('44,9,44,16.98,53'); // 查看计算后的执行工时汇总信息
