#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$planid = array();
$planid[0] = 0;
$planid[1] = 1;

r($plan->getChildren(0)) && p() && e('70'); //这里统计了取出所有的数量
r($plan->getChildren(1)) && p() && e('0');  //这里统计不存在的情况
?>
