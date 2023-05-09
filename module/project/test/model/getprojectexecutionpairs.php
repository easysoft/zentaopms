#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(6);

/**

title=测试 projectModel->getProjectExecutionPairs();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$project = $tester->project->getProjectExecutionPairs();

r(count($project)) && p() && e('6');
