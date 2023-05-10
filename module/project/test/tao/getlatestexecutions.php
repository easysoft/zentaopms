#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::getLatestExecutions();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');
$executions = $tester->project->getLatestExecutions();

r($executions[3]->name) && p() && e('项目3');
r($executions[3]->code) && p() && e('project3');
