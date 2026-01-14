#!/usr/bin/env php
<?php
/**

title=productpanModel->batchChangeStatus();
timeout=0
cid=17631

- 如果存在，返回数组类型数据属性status @wait
- 取出开始时间属性begin @2021-06-13
- 取出结束时间属性end @2021-10-14
- 如不存在，返回布尔值 @

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$plan = new productPlan('admin');

$planID = array();
$planID[0] = 5;
$planID[1] = 1000;

r($plan->getByIDPlan($planID[0])) && p('status') && e('wait');       //如果存在，返回数组类型数据
r($plan->getByIDPlan($planID[0])) && p('begin')  && e('2021-06-13'); //取出开始时间
r($plan->getByIDPlan($planID[0])) && p('end')    && e('2021-10-14'); //取出结束时间
r($plan->getByIDPlan($planID[1])) && p()         && e('0');          //如不存在，返回布尔值
?>
