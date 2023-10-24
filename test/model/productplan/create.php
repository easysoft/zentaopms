#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->create();
cid=1
pid=1

测试正常创建 >> 110
测试不传入名称的情况 >> 『名称』不能为空。
测试不传开始时间的情况 >> 111
测试不传结束时间的情况 >> 112
测试不传开始时间及结束时间的情况 >> 113
测试不传UID的情况 >> 114
测试不传关联产品的情况 >> 『产品』应当是数字。
测试不传父级计划的情况 >> 『父计划』应当是数字。

*/
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

r($plan->create($posts))      && p()            && e('110');                    //测试正常创建
r($plan->create($noTitle))    && p('title:0')   && e('『名称』不能为空。');     //测试不传入名称的情况
r($plan->create($noBegin))    && p()            && e('111');                    //测试不传开始时间的情况
r($plan->create($noEnd))      && p()            && e('112');                    //测试不传结束时间的情况
r($plan->create($noBeginEnd)) && p()            && e('113');                    //测试不传开始时间及结束时间的情况
r($plan->create($noUid))      && p()            && e('114');                    //测试不传UID的情况
r($plan->create($noProduct))  && p('product:0') && e('『产品』应当是数字。');   //测试不传关联产品的情况
r($plan->create($noParent))   && p('parent:0')  && e('『父计划』应当是数字。'); //测试不传父级计划的情况
?>
