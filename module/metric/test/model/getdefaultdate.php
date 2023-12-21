#!/usr/bin/env php
<?php
/**
title=getDefaultDate
timeout=0
cid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$yearLabels = array();
$yearLabels['3']   = '近3年';
$yearLabels['5']   = '近5年';
$yearLabels['10']  = '近10年';
$yearLabels['all'] = '全部';

$monthLabels = array();
$monthLabels['6']   = '近6个月';
$monthLabels['12']  = '近12个月';
$monthLabels['24']  = '近24个月';
$monthLabels['36']  = '近36个月';

$weekLabels = array();
$weekLabels['4']  = '近4周';
$weekLabels['8']  = '近8周';
$weekLabels['12'] = '近12周';
$weekLabels['16'] = '近16周';

$dayLabels = array();
$dayLabels['7']  = '近7天';
$dayLabels['14'] = '近14天';
$dayLabels['21'] = '近21天';
$dayLabels['28'] = '近28天';

$emptyLabels = array();
r($metric->getDefaultDate($yearLabels))  && p('') && e('3'); // 测试传入年度筛选器标签
r($metric->getDefaultDate($monthLabels)) && p('') && e('6'); // 测试传入月度筛选器标签
r($metric->getDefaultDate($weekLabels))  && p('') && e('4'); // 测试传入周度筛选器标签
r($metric->getDefaultDate($dayLabels))   && p('') && e('7'); // 测试传入日度筛选器标签
r($metric->getDefaultDate($emptyLabels)) && p('') && e('0'); // 测试传入空数组
