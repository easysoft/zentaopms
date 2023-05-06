#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

$project = zdTable('project');
$project->type->range('sprint');
$project->gen(8);

/**

title=测试 projectTao::getLatestExecutions();
timeout=0
cid=1
- 执行executions[3]模块的name方法 @项目3

- 执行executions[3]模块的code方法 @project3

*/

global $tester;
$tester->loadModel('project');
$executions = $tester->project->getLatestExecutions();
r($executions[3]->name) && p() && e('项目3');
r($executions[3]->code) && p() && e('project3');
