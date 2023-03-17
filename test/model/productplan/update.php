#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->update();
cid=1
pid=1

修改planId=5的数据,打印出旧的名称 >> 1.1
二次修改旧的数据，由于数据发生变化，理应失败 >> 0
测试传入结束时间小于开始时间的情况 >> 父计划[测试修改]的完成日期：2021-10-10，不能小于子计划的完成日期: 2022-04-01

*/
$plan = new productPlan('admin');

$planId = array();
$planId[0] = 5;
$planId[1] = 6;

$posts = array();
$posts['title']   = '测试修改';
$posts['status']  = 'doing';
$posts['begin']   = '2021-03-22';
$posts['end']     = '2022-10-31';
$posts['uid']     = '';
$posts['product'] = '2';

$late = $posts;
$late['end']      = '2021-10-10';

r($plan->update($planId[0], $posts)) && p('0:old') && e('1.1'); //修改planId=5的数据,打印出旧的名称
r($plan->update($planId[0], $posts)) && p('0:old') && e('0');   //二次修改旧的数据，由于数据发生变化，理应失败
r($plan->update($planId[0], $late))  && p('end')   && e('父计划[测试修改]的完成日期：2021-10-10，不能小于子计划的完成日期: 2022-04-01');   //测试传入结束时间小于开始时间的情况
?>
