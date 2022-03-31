#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$planID = array();
$planID[0] = 5;
$planID[1] = 1000;

r($plan->getByIDPlan($planID[0])) && p('status') && e('wait');       //如果存在，返回数组类型数据
r($plan->getByIDPlan($planID[0])) && p('begin')  && e('2021-06-13'); //取出开始时间
r($plan->getByIDPlan($planID[0])) && p('end')    && e('2021-10-14'); //取出结束时间
r($plan->getByIDPlan($planID[1])) && p()         && e('0');          //如不存在，返回布尔值
?>
