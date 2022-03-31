#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productplanModel->reorder4Children();
cid=1
pid=1

传入三个子计划id100,102,101,排序第一个 >> 100
第二个 >> 101
第三个，会输出正序排列的数组100，101，102 >> 102
传入不存在的id时 >> 0

*/

$plan = new productPlan('admin');

$planID = array();
$planID[0] = array(100, 102, 101);
$planID[1] = array(1000, 10000);

r($plan->reorder4Children($planID[0])) && p('100:id') && e('100');  //传入三个子计划id100,102,101,排序第一个
r($plan->reorder4Children($planID[0])) && p('101:id') && e('101');  //第二个
r($plan->reorder4Children($planID[0])) && p('102:id') && e('102');  //第三个，会输出正序排列的数组100，101，102
r($plan->reorder4Children($planID[1])) && p()         && e('0'); //传入不存在的id时
?>