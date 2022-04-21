#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getUserEffortHours();
cid=1
pid=1

正常传入的情况 >> 0
传入不存在的情况 >> 0

*/

$personnel = new personnelTest('admin');

$projectID = array();
$projectID[0] = 11;
$projectID[1] = 11111;

$result1 = $personnel->getUserEffortHoursTest($projectID[0]);
$result2 = $personnel->getUserEffortHoursTest($projectID[1]);

r($result1) && p() && e('0'); //正常传入的情况
r($result2) && p() && e('0'); //传入不存在的情况