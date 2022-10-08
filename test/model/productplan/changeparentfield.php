#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(Dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productplanModel->changeParentField();
cid=1
pid=1

传入ID为1的情况，返回true >> 1
传入ID为5的情况，返回true，如不存在函数会报错 >> 1

*/

$plan = new productPlan('admin');

$planID = array();
$planID[0] = 1;
$planID[1] = 5;

r($plan->changeParentField($planID[0])) && p() && e('1'); //传入ID为1的情况，返回true
r($plan->changeParentField($planID[1])) && p() && e('1'); //传入ID为5的情况，返回true，如不存在函数会报错
?>