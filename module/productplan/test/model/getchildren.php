#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

/**

title=productpanModel->getChildren();
cid=1
pid=1

这里统计了取出所有的数量 >> 61
这里统计不存在的情况 >> 1

*/
$plan = new productPlan('admin');

$planid = array();
$planid[0] = 0;
$planid[1] = 1;

r($plan->getChildren(0)) && p() && e('61'); //这里统计了取出所有的数量
r($plan->getChildren(1)) && p() && e('1');  //这里统计不存在的情况
?>