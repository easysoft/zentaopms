#!/usr/bin/env php
<?php
/**

title=productplanModel->reorder4Children();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->gen(150);

$plan = new productPlan('admin');

$planID = array();
$planID[0] = array(100, 102, 101);
$planID[1] = array(1000, 10000);

r($plan->reorder4ChildrenTest($planID[0])) && p('100:id') && e('100');  //传入三个子计划id100,102,101,排序第一个
r($plan->reorder4ChildrenTest($planID[0])) && p('101:id') && e('101');  //第二个
r($plan->reorder4ChildrenTest($planID[0])) && p('102:id') && e('102');  //第三个，会输出正序排列的数组100，101，102
r($plan->reorder4ChildrenTest($planID[1])) && p()         && e('0');    //传入不存在的id时
