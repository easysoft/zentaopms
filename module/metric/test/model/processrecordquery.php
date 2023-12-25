#!/usr/bin/env php
<?php
/**
title=processRecordQuery
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$query1 = array('scope' => '1,2');
$query2 = array('dateBegin' => '2023-12-04', 'dateEnd' => '2023-12-21', 'dateType' => 'year');
$query3 = array('scope' => '3', 'dateLabel' => 'all', 'dateType' => 'year');
$query4 = array('calcDate' => '2023-12-24', 'dateType' => 'day');
r($metric->processRecordQuery($query1, 'scope')) && p() && e('1,2'); // 获取query1的scope字段
r($metric->processRecordQuery($query2, 'scope')) && p() && e('0');   // 获取query2的scope字段
r($metric->processRecordQuery($query2, 'dateBegin', 'date')) && p('year,month,week,day') && e('2023,202312,202349,20231204'); // 获取query2的dateBegin字段
r($metric->processRecordQuery($query2, 'dateEnd', 'date')) && p('year,month,week,day') && e('2023,202312,202351,20231221');   // 获取query2的dateEnd字段
r($metric->processRecordQuery($query2, 'dateLabel', 'date')) && p('0:year,month,week,day;1:year,month,week,day') && e('2023,202312,202349,20231204;2023,202312,202351,20231221'); // 获取query2的dateLabel字段
r($metric->processRecordQuery($query3, 'scope')) && p() && e('3');  // 获取query3的scope字段
r($metric->processRecordQuery($query3, 'dateBegin', 'date')) && p('year,month,week,day') && e('0,0,0,0'); // 获取query3的dateBegin字段
r($metric->processRecordQuery($query3, 'dateEnd', 'date')) && p('year,month,week,day') && e('0,0,0,0');   // 获取query3的dateEnd字段
r($metric->processRecordQuery($query3, 'dateLabel', 'date')) && p('0:year,month,week,day') && e('1970,197001,197001,19700101'); // 获取query3的dateLabel字段
r($metric->processRecordQuery($query4, 'calcDate', 'date')) && p('year,month,week,day') && e('2023,202312,202351,20231224');    // 获取query4的calcDate字段
