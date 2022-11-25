#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getStatData();
cid=1
pid=1

统计id=13的项目bug数量 >> 4
任务数量 >> 13
未开始数量 >> 2
进行的数量 >> 2
完成的数量 >> 0

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getStatData(13)) && p('bugCount')       && e('4');  //统计id=13的项目bug数量
r($tester->project->getStatData(13)) && p('taskCount')      && e('13'); //任务数量
r($tester->project->getStatData(13)) && p('waitCount')      && e('2');  //未开始数量
r($tester->project->getStatData(13)) && p('doingCount')     && e('2');  //进行的数量
r($tester->project->getStatData(13)) && p('finishedCount')  && e('0');  //完成的数量