#!/usr/bin/env php
<?php
/**
title=setDefaultOptions
timeout=0
cid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$optionsList = array();
$optionsList[0] = array('product' => '1', 'year' => '2023');
$optionsList[1] = array('product' => '2', 'year' => '2023');

$dateList = array();
$dateList[0] = array('year');
$dateList[1] = array('year', 'month');
$dateList[2] = array('week', 'year');
$dateList[3] = array('day', 'year');
$dateList[4] = array('day', 'month', 'week');
$dateList[5] = array('year', 'month', 'week');

r($metric->setDefaultOptions($optionsList[0], $dateList[0])) && p('product,year') && e('1,2023'); // 测试传入options的情况1
r($metric->setDefaultOptions($optionsList[1], $dateList[1])) && p('product,year') && e('2,2023'); // 测试传入options的情况2

r($metric->setDefaultOptions(array(), $dateList[0])) && p('dateType,dateLabel') && e('year,3');  // 测试不传入options的情况1
r($metric->setDefaultOptions(array(), $dateList[1])) && p('dateType,dateLabel') && e('month,6'); // 测试不传入options的情况2
r($metric->setDefaultOptions(array(), $dateList[2])) && p('dateType,dateLabel') && e('week,4');  // 测试不传入options的情况3
r($metric->setDefaultOptions(array(), $dateList[3])) && p('dateType,dateLabel') && e('day,7');   // 测试不传入options的情况4
r($metric->setDefaultOptions(array(), $dateList[4])) && p('dateType,dateLabel') && e('day,7');   // 测试不传入options的情况5
r($metric->setDefaultOptions(array(), $dateList[5])) && p('dateType,dateLabel') && e('week,4');  // 测试不传入options的情况6
