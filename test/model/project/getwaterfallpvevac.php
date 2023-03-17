#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getWaterfallPVEVAC();
cid=1
pid=1

测试projectID值为0  >> 0.00,0.00,0.00
测试projectID值为11 >> 97.00,53.94,108.00
测试projectID值为41 >> 52.00,19.87,33.00

*/

$projectIDList = array(0, 11, 41);

global $tester;
$tester->loadModel('project');

r($tester->project->getWaterfallPVEVAC($projectIDList[0])) && p('PV,EV,AC') && e('0.00,0.00,0.00');      //测试projectID值为0
r($tester->project->getWaterfallPVEVAC($projectIDList[1])) && p('PV,EV,AC') && e('97.00,53.94,108.00');  //测试projectID值为11
r($tester->project->getWaterfallPVEVAC($projectIDList[2])) && p('PV,EV,AC') && e('52.00,19.87,33.00');   //测试projectID值为41

