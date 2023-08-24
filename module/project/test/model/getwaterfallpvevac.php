#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 projectModel->getWaterfallPVEVAC();
timeout=0
cid=1

*/

zdTable('task')->config('task')->gen(10);
zdTable('project')->config('execution')->gen(10);
zdTable('effort')->config('effort')->gen(10);

$projectIDList = array(0, 11, 60);

global $tester;
$tester->loadModel('project');

r($tester->project->getWaterfallPVEVAC($projectIDList[0])) && p('PV,EV,AC') && e('0.00,0.00,0.00');   // 测试projectID值为0
r($tester->project->getWaterfallPVEVAC($projectIDList[1])) && p('PV,EV,AC') && e('28.00,8.85,25.00'); // 测试projectID值为11
r($tester->project->getWaterfallPVEVAC($projectIDList[2])) && p('PV,EV,AC') && e('0.00,0.00,5.00');   // 测试projectID值为60
