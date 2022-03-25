#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$posts = array();
$posts['title']   = '测试创建1';
$posts['begin']   = '2021-10-25';
$posts['end']     = '2021-10-29';
$posts['uid']     = '623927843dd9b';
$posts['product'] = '2';
$posts['parent']  = '0';

$noTitle = $posts;
$noTitle['title']  = '';

$noBegin = $posts;
$noBegin['begin']  = '';

$noEnd   = $posts;
$noEnd['end']      = '';

$noBeginEnd = $noBegin;
$noBeginEnd['end'] = '';

$noUid   = $posts;
$noUid['uid']      = '';

$noProduct = $posts;
$noProduct['product'] = '';

$noParent = $posts;
$noParent['parent'] = '';

r($plan->create($posts))      && p() && e('71'); //测试正常创建
r($plan->create($noTitle))    && p() && e('0');  //测试不传入名称的情况
r($plan->create($noBegin))    && p() && e('0');  //测试不传开始时间的情况
r($plan->create($noEnd))      && p() && e('0');  //测试不传结束时间的情况
r($plan->create($noBeginEnd)) && p() && e('0');  //测试不传开始时间及结束时间的情况
r($plan->create($noUid))      && p() && e('0');  //测试不传UID的情况
r($plan->create($noProduct))  && p() && e('0');  //测试不传关联产品的情况
r($plan->create($noParent))   && p() && e('0');  //测试不传父级计划的情况
?>
