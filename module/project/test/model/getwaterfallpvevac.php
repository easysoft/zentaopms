#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 projectModel->getWaterfallPVEVAC();
timeout=0
cid=17858

- 测试projectID值为0
 - 属性PV @0.00
 - 属性EV @0.00
 - 属性AC @0.00
- 测试projectID值为11
 - 属性PV @33.00
 - 属性EV @8.85
 - 属性AC @25.00
- 测试projectID值为60
 - 属性PV @0.00
 - 属性EV @0.00
 - 属性AC @5.00

*/

zenData('task')->loadYaml('task')->gen(10);
zenData('project')->loadYaml('execution')->gen(10);
zenData('effort')->loadYaml('effort')->gen(10);

$projectIDList = array(0, 11, 60);

global $tester;
$tester->loadModel('project');

r($tester->project->getWaterfallPVEVAC($projectIDList[0])) && p('PV,EV,AC') && e('0.00,0.00,0.00');   // 测试projectID值为0
r($tester->project->getWaterfallPVEVAC($projectIDList[1])) && p('PV,EV,AC') && e('33.00,8.85,25.00'); // 测试projectID值为11
r($tester->project->getWaterfallPVEVAC($projectIDList[2])) && p('PV,EV,AC') && e('0.00,0.00,5.00');   // 测试projectID值为60
