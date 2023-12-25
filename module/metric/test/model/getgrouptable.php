#!/usr/bin/env php
<?php
/**
title=getGroupTable
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$resultHeader1[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader1[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);
$resultData1[] = (object) array('value' => 2, 'calcTime' => '2021-11-23 0:01');
$resultData1[] = (object) array('value' => 2, 'calcTime' => '2021-12-22 1:23');
$resultData1[] = (object) array('value' => 2, 'calcTime' => '2021-12-23 1:11');
$resultData1[] = (object) array('value' => 2, 'calcTime' => '2022-9-23 2:22');
$resultData1[] = (object) array('value' => 2, 'calcTime' => '2022-10-23 0:01');
$resultData1[] = (object) array('value' => 2, 'calcTime' => '2022-11-23 0:00');
$resultData1[] = (object) array('value' => 3, 'calcTime' => '2023-10-21 0:02');
$resultData1[] = (object) array('value' => 1, 'calcTime' => '2023-11-23 0:00');

$resultHeader2[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$resultHeader2[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader2[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader2[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);
$resultData2[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'scope' => '开源', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData2[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'scope' => 'ddddw', 'value' => 3, 'calcTime' => '2021-11-23 0:23');
$resultData2[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'scope' => '开源', 'value' => 2, 'calcTime' => '2022-11-23 0:23');
$resultData2[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'scope' => 'ddddw', 'value' => 9, 'calcTime' => '2022-11-23 0:23');
$resultData2[] = (object) array('date' => '2023年', 'dateString' => '2023', 'dateType' => 'year', 'scope' => '开源', 'value' => 3, 'calcTime' => '2023-11-23 0:23');

$resultHeader3[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader3[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader3[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);
$resultData3[] = (object) array('date' => '2021-11-12', 'dateString' => '2021-11-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2022-11-12', 'dateString' => '2022-11-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2022-12-11', 'dateString' => '2022-12-11', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-1-12',  'dateString' => '2023-1-12',  'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-12', 'dateString' => '2023-10-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-13', 'dateString' => '2023-10-13', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-14', 'dateString' => '2023-10-14', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-15', 'dateString' => '2023-10-15', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-18', 'dateString' => '2023-10-18', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-19', 'dateString' => '2023-10-19', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-10-20', 'dateString' => '2023-10-20', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-11-12', 'dateString' => '2023-11-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData3[] = (object) array('date' => '2023-11-13', 'dateString' => '2023-11-13', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');

r($metric->getGroupTable($resultHeader1, $resultData1)) && p('0:name;1:name') && e('date,value');                 // 传入resultHeader1和resultData1，获取header
r($metric->getGroupTable($resultHeader1, $resultData1, true, false)) && p('0:value;1:value') && e('Array,Array'); // 传入resultHeader1和resultData1，获取data
r($metric->getGroupTable($resultHeader1, $resultData1, false, false)) && p('0:value;1:value') && e('2,2');        // 传入resultHeader1和resultData1，获取data

r($metric->getGroupTable($resultHeader2, $resultData2)) && p('0:name;1:name') && e('scope,2023');                                      // 传入resultHeader2和resultData2，获取header
r($metric->getGroupTable($resultHeader2, $resultData2, true, false)) && p('0:scope,2023;1:scope,2022') && e('开源,Array;ddddw,Array'); // 传入resultHeader2和resultData2，获取header
r($metric->getGroupTable($resultHeader2, $resultData2, false, false)) && p('0:scope,2023;1:scope,2022') && e('开源,3;ddddw,9');        // 传入resultHeader2和resultData2，获取header

r($metric->getGroupTable($resultHeader3, $resultData3)) && p('0:name;1:name') && e('date,value');                 // 传入resultHeader2和resultData2，获取header
r($metric->getGroupTable($resultHeader3, $resultData3, true, false)) && p('0:value;1:value') && e('Array,Array'); // 传入resultHeader2和resultData2，获取header
r($metric->getGroupTable($resultHeader3, $resultData3, false, false)) && p('0:value;1:value') && e('1;1');         // 传入resultHeader2和resultData2，获取header
