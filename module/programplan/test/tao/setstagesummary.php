#!/usr/bin/env php
<?php

/**

title=测试 loadModel->setStageSummary()
cid=0

- 传入空数据 @0
- 传入gantt空数据 @0
- 传入正常数据
 - 第1条的progress属性 @0.6
 - 第1条的taskProgress属性 @60%
 - 第1条的estimate属性 @110
 - 第1条的consumed属性 @60

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('programplan');

$stages = array();
$stages['1']['totalReal']     = 100;
$stages['1']['totalEstimate'] = 110;
$stages['1']['totalConsumed'] = 60;

$ganttData = array();
$ganttData['data'][1] = new stdclass;

r($tester->programplan->setStageSummary(array(), array()))    && p() && e('0'); //传入空数据
r($tester->programplan->setStageSummary(array(), $stages))    && p() && e('0'); //传入gantt空数据
r($tester->programplan->setStageSummary($ganttData, $stages)['data']) && p('1:progress,taskProgress,estimate,consumed') && e('0.6,60%,110,60'); //传入正常数据
