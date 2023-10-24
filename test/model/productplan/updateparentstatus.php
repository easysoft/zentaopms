#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->updateParentStatus();
cid=1
pid=1

将id为1的计划改为wait状态 >> 0
将id为2的计划改为doing状态 >> 1
将id为3的计划改为closed状态 >> 1
将id为4的计划改为done状态 >> 1

*/
$plan = new productPlan('admin');

$parentId = array();
$parentid[0] = 1;
$parentid[1] = 2;
$parentid[2] = 3;
$parentid[3] = 4;

#此方法将父级plan状态改为与子plan相同的状态
r($plan->updateParentStatus($parentid[0])) && p() && e('0'); //将id为1的计划改为wait状态
r($plan->updateParentStatus($parentid[1])) && p() && e('1'); //将id为2的计划改为doing状态
r($plan->updateParentStatus($parentid[2])) && p() && e('1'); //将id为3的计划改为closed状态
r($plan->updateParentStatus($parentid[3])) && p() && e('1'); //将id为4的计划改为done状态
?>
