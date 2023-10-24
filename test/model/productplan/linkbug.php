#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productplanModel->linkBug()
cid=1
pid=1

id为100的计划关联id为3的bug >> 0
id为100的计划关联id为1和2的bug >> 0
传入不存在的id >> 0
解除bugid为1的关联关系 >> 0
解除bugid为2的关联关系 >> 0
解除不存在的id >> 0

*/

$plan = new productPlan('admin');

$planID = array();
$planID[0] = 100;

$bugID = array();
$bugID[0] = array('bugs' => array(3));
$bugID[1] = array('bugs' => array(1, 2));
$bugID[2] = array('bugs' => array(10000));

$unbugID = array();
$unbugID[0] = 1;
$unbugID[1] = 2;
$unbugID[2] = 10000;

r($plan->linkBug($planID[0], $bugID[0]))     && p() && e('0'); //id为100的计划关联id为3的bug
r($plan->linkBug($planID[0], $bugID[1]))     && p() && e('0'); //id为100的计划关联id为1和2的bug
r($plan->linkBug($planID[0], $bugID[2]))     && p() && e('0'); //传入不存在的id
r($plan->unlinkBug($planID[0], $unbugID[0])) && p() && e('0'); //解除bugid为1的关联关系
r($plan->unlinkBug($planID[0], $unbugID[1])) && p() && e('0'); //解除bugid为2的关联关系
r($plan->unlinkBug($planID[0], $unbugID[2])) && p() && e('0'); //解除不存在的id
?>
