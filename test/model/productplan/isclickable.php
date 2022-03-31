#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productplanModel->isClickable();
cid=1
pid=1

id=100,无子集计划，可以点击的id=100 >> 1
id=1,有子集计划,不能点击 >> 0
id=102,无子集计划，计划关闭，不可点击 >> 0
id=105,无子集计划，等待状态，不可点击 >> 0

*/

$plan = new productPlan('admin');

$planID = array();
$planID[0] = 100;
$planID[1] = 101;
$planID[2] = 1;
$planID[3] = 102;
$planID[4] = 105;

$action = array();
$action[0] = 'start';
$action[1] = 'finish';
$action[2] = 'close';
$action[3] = 'activate';

r($plan->isClickable($planID[0], $action[0])) && p() && e('1'); //id=100,无子集计划，可以点击的id=100
r($plan->isClickable($planID[2], $action[1])) && p() && e('0'); //id=1,有子集计划,不能点击
r($plan->isClickable($planID[3], $action[2])) && p() && e('0'); //id=102,无子集计划，计划关闭，不可点击
r($plan->isClickable($planID[4], $action[3])) && p() && e('0'); //id=105,无子集计划，等待状态，不可点击
?>