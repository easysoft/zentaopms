<?php

/* 适用于按日统计的对象个数，例如 按系统统计的每日关闭Bug数 */

$resultHeader = array();
$resultData   = array();

$resultHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$resultData[] = (object) array('date' => '2021-11-12', 'dateString' => '2021-11-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2022-11-12', 'dateString' => '2022-11-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2022-12-11', 'dateString' => '2022-12-11', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-1-12',  'dateString' => '2023-1-12',  'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-12', 'dateString' => '2023-10-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-13', 'dateString' => '2023-10-13', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-14', 'dateString' => '2023-10-14', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-15', 'dateString' => '2023-10-15', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-18', 'dateString' => '2023-10-18', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-19', 'dateString' => '2023-10-19', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-10-20', 'dateString' => '2023-10-20', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-11-12', 'dateString' => '2023-11-12', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2023-11-13', 'dateString' => '2023-11-13', 'dateType' => 'day', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
