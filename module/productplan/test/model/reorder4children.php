#!/usr/bin/env php
<?php
/**

title=productplanModel->reorder4Children();
timeout=0
cid=17646

- 传入四个子计划id100,102,101,排序第一个第100条的id属性 @100
- 第二个第101条的id属性 @101
- 第三个第102条的id属性 @102
- 第四个，会输出正序排列的数组100，101，102, 103第103条的id属性 @103
- 传入不存在的id时 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('productplan')->gen(150);

$plan = new productPlan('admin');

$planID = array();
$planID[0] = array(100, 102, 103, 101);
$planID[1] = array(1000, 10000);

r($plan->reorder4ChildrenTest($planID[0])) && p('100:id') && e('100');  //传入四个子计划id100,102,101,排序第一个
r($plan->reorder4ChildrenTest($planID[0])) && p('101:id') && e('101');  //第二个
r($plan->reorder4ChildrenTest($planID[0])) && p('102:id') && e('102');  //第三个
r($plan->reorder4ChildrenTest($planID[0])) && p('103:id') && e('103');  //第四个，会输出正序排列的数组100，101，102, 103
r($plan->reorder4ChildrenTest($planID[1])) && p()         && e('0');    //传入不存在的id时
