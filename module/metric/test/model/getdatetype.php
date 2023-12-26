#!/usr/bin/env php
<?php

/**

title=getDateType
timeout=0
cid=1

- 测试传入day @day
- 测试传入month @month
- 测试传入week @week
- 测试传入year @year
- 测试传入day,month @day
- 测试传入month,week @week
- 测试传入week,year @week
- 测试传入day,year @day
- 测试传入day,month,week @day
- 测试传入year,month,year @week
- 测试传入year,month,year @month
- 测试传入day,month,week,year @day
- 测试传入scope @nodate

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$day   = array('day');
$month = array('month');
$week  = array('week');
$year  = array('year');

$dateList = array();
$dateList[0] = array('day', 'month');
$dateList[1] = array('month', 'week');
$dateList[2] = array('week', 'year');
$dateList[3] = array('day', 'year');
$dateList[4] = array('day', 'month', 'week');
$dateList[5] = array('year', 'month', 'week');
$dateList[6] = array('year', 'month', 'year');
$dateList[7] = array('day', 'month', 'week', 'year');
$dateList[8] = array('product');

r($metric->getDateType($day))         && p('') && e('day');    // 测试传入day
r($metric->getDateType($month))       && p('') && e('month');  // 测试传入month
r($metric->getDateType($week))        && p('') && e('week');   // 测试传入week
r($metric->getDateType($year))        && p('') && e('year');   // 测试传入year
r($metric->getDateType($dateList[0])) && p('') && e('day');    // 测试传入day,month
r($metric->getDateType($dateList[1])) && p('') && e('week');   // 测试传入month,week
r($metric->getDateType($dateList[2])) && p('') && e('week');   // 测试传入week,year
r($metric->getDateType($dateList[3])) && p('') && e('day');    // 测试传入day,year
r($metric->getDateType($dateList[4])) && p('') && e('day');    // 测试传入day,month,week
r($metric->getDateType($dateList[5])) && p('') && e('week');   // 测试传入year,month,year
r($metric->getDateType($dateList[6])) && p('') && e('month');  // 测试传入year,month,year
r($metric->getDateType($dateList[7])) && p('') && e('day');    // 测试传入day,month,week,year
r($metric->getDateType($dateList[8])) && p('') && e('nodate'); // 测试传入scope