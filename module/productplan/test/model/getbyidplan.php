#!/usr/bin/env php
<?php

/**

title=测试productplanModel->getByIDPlan();
timeout=0
cid=17631

- 如果存在，返回数组类型数据属性status @wait
- 取出开始时间属性begin @2021-01-01
- 取出结束时间属性end @2021-06-30
- 如不存在，返回布尔值 @0
- 如不存在且设置图片尺寸，返回布尔值 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(5);
$planTester = new productplanModelTest('admin');

$planID = array();
$planID[0] = 1;
$planID[1] = 1000;

r($planTester->getByIDPlan($planID[0])) && p('status') && e('wait');       //如果存在，返回数组类型数据
r($planTester->getByIDPlan($planID[0])) && p('begin')  && e('2021-01-01'); //取出开始时间
r($planTester->getByIDPlan($planID[0])) && p('end')    && e('2021-06-30'); //取出结束时间
r($planTester->getByIDPlan($planID[1])) && p()         && e('0');          //如不存在，返回布尔值
r($planTester->getByIDPlan($planID[1], true)) && p()         && e('0');          //如不存在且设置图片尺寸，返回布尔值
?>