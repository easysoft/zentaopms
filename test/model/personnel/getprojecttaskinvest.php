#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->getProjectTaskInvest();
cid=1
pid=1

次方法为页面上的项目信息，人员，创建人物 >> 0
次方法为页面上的项目信息，人员，已完成任务 >> 0
次方法为页面上的项目信息，人员，待处理 >> 0
次方法为页面上的项目信息，人员，任务消耗 >> 0

*/

$personnel = new personnelTest('admin');

$projectID = array();
$projectID[0] = 11;
$projectID[1] = 12;

$account = array();
$account[0] = 'admin';
$account[1] = 'user3';

$result1 = $personnel->getProjectTaskInvestTest($projectID, $account);

r($result1) && p('0:createdTask')  && e('0'); //次方法为页面上的项目信息，人员，创建人物
r($result1) && p('0:finishedTask') && e('0'); //次方法为页面上的项目信息，人员，已完成任务
r($result1) && p('0:pendingTask')  && e('0'); //次方法为页面上的项目信息，人员，待处理
r($result1) && p('0:consumedTask') && e('0'); //次方法为页面上的项目信息，人员，任务消耗