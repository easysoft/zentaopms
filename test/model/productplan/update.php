#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$planId = array();
$planId[0] = 5;
$planId[1] = 6;

$posts = array();
$posts['title']   = '测试修改';
$posts['status']  = 'doing';
$posts['begin']   = '2022-03-22';
$posts['end']     = '2022-10-31';
$posts['uid']     = '';
$posts['product'] = '2';

$late = $posts;
$late['end']      = '2021-10-10';

r($plan->update($planId[0], $posts)) && p('0:old') && e('1.1'); //修改planId=5的数据,打印出旧的名称
r($plan->update($planId[0], $posts)) && p('0:old') && e('0');   //二次修改旧的数据，由于数据发生变化，理应失败
r($plan->update($planId[0], $late))  && p()        && e('0');   //测试传入结束时间小于开始时间的情况
?>
