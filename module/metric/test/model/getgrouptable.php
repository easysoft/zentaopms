#!/usr/bin/env php
<?php

/**

title=getGroupTable
timeout=0
cid=17100

- 传入resultHeader1和resultData1，获取header
 - 第0条的name属性 @date
 - 第1条的name属性 @value
- 传入resultHeader1和resultData1，获取data[0]第value条的0属性 @1
- 传入resultHeader1和resultData1，获取data[1]第value条的0属性 @3
- 传入resultHeader2和resultData2，获取header
 - 第0条的name属性 @scope
 - 第1条的name属性 @2023
- 传入resultHeader2和resultData2，获取data[0]
 - 属性scope @开源
 - 第2023条的0属性 @3
- 传入resultHeader2和resultData2，获取data[1]
 - 属性scope @ddddw
 - 第2022条的0属性 @9
- 传入resultHeader2和resultData2，获取header
 - 第0条的name属性 @date
 - 第1条的name属性 @value
- 传入resultHeader2和resultData2，获取data[0]第value条的0属性 @1
- 传入resultHeader2和resultData2，获取data[1]第value条的0属性 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

$resultHeader1[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader1[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);
$resultData1[] = (object) array('value' => 2, 'dateString' => '2021-11-23', 'calcTime' => '2021-11-23 0:01');
$resultData1[] = (object) array('value' => 2, 'dateString' => '2021-12-22', 'calcTime' => '2021-12-22 1:23');
$resultData1[] = (object) array('value' => 2, 'dateString' => '2021-12-23', 'calcTime' => '2021-12-23 1:11');
$resultData1[] = (object) array('value' => 2, 'dateString' => '2022-9-23',  'calcTime' => '2022-9-23 2:22');
$resultData1[] = (object) array('value' => 2, 'dateString' => '2022-10-23', 'calcTime' => '2022-10-23 0:01');
$resultData1[] = (object) array('value' => 2, 'dateString' => '2022-11-23', 'calcTime' => '2022-11-23 0:00');
$resultData1[] = (object) array('value' => 3, 'dateString' => '2023-10-23', 'calcTime' => '2023-10-21 0:02');
$resultData1[] = (object) array('value' => 1, 'dateString' => '2023-11-23', 'calcTime' => '2023-11-23 0:00');

foreach($resultData1 as $index => $data)
{
    $resultData1[$index]->calcType     = 'cron';
    $resultData1[$index]->calculatedBy = 'system';
}

$resultHeader2[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$resultHeader2[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader2[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader2[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);
$resultData2[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'scope' => '开源', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData2[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'scope' => 'ddddw', 'value' => 3, 'calcTime' => '2021-11-23 0:23');
$resultData2[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'scope' => '开源', 'value' => 2, 'calcTime' => '2022-11-23 0:23');
$resultData2[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'scope' => 'ddddw', 'value' => 9, 'calcTime' => '2022-11-23 0:23');
$resultData2[] = (object) array('date' => '2023年', 'dateString' => '2023', 'dateType' => 'year', 'scope' => '开源', 'value' => 3, 'calcTime' => '2023-11-23 0:23');

foreach($resultData2 as $index => $data)
{
    $resultData2[$index]->calcType     = 'cron';
    $resultData2[$index]->calculatedBy = 'system';
}

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

foreach($resultData3 as $index => $data)
{
    $resultData3[$index]->calcType     = 'cron';
    $resultData3[$index]->calculatedBy = 'system';
}

$group1 = (array)$metric->getGroupTable($resultHeader1, $resultData1);
r($group1) && p('0:name;1:name') && e('date,value'); // 传入resultHeader1和resultData1，获取header

$group1 = $metric->getGroupTable($resultHeader1, $resultData1, false, false);
r($group1[0]) && p('value:0') && e('1'); // 传入resultHeader1和resultData1，获取data[0]
r($group1[1]) && p('value:0') && e('3'); // 传入resultHeader1和resultData1，获取data[1]

$group2 = $metric->getGroupTable($resultHeader2, $resultData2);
r($group2) && p('0:name;1:name') && e('scope,2023'); // 传入resultHeader2和resultData2，获取header

$group2 = $metric->getGroupTable($resultHeader2, $resultData2, false, false);
r($group2[0]) && p('scope;2023:0') && e('开源,3');  // 传入resultHeader2和resultData2，获取data[0]
r($group2[1]) && p('scope;2022:0') && e('ddddw,9'); // 传入resultHeader2和resultData2，获取data[1]

$group3 = (array)$metric->getGroupTable($resultHeader3, $resultData3);
r($group3) && p('0:name;1:name') && e('date,value'); // 传入resultHeader2和resultData2，获取header

$group3 = $metric->getGroupTable($resultHeader3, $resultData3, false, false);
r($group3[0]) && p('value:0') && e('1');         // 传入resultHeader2和resultData2，获取data[0]
r($group3[1]) && p('value:0') && e('1');         // 传入resultHeader2和resultData2，获取data[1]