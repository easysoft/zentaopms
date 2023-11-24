<?php

/* 适用于按月统计的对象个数，例如 按系统统计的每月新增发布数 */

$resultHeader = array();
$resultData   = array();

$resultHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$resultData[] = (object) array('date' => '2021年11月', 'dateString' => '2021-11', 'dateType' => 'month', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2022年11月', 'dateString' => '2022-11', 'dateType' => 'month', 'value' => 2, 'calcTime' => '2022-11-23 0:23');
$resultData[] = (object) array('date' => '2023年5月',  'dateString' => '2023-5',  'dateType' => 'month', 'value' => 3, 'calcTime' => '2023-11-23 0:23');
$resultData[] = (object) array('date' => '2023年6月',  'dateString' => '2023-6',  'dateType' => 'month', 'value' => 2, 'calcTime' => '2023-11-23 0:23');
$resultData[] = (object) array('date' => '2023年7月',  'dateString' => '2023-7',  'dateType' => 'month', 'value' => 1, 'calcTime' => '2023-11-23 0:23');
$resultData[] = (object) array('date' => '2023年8月',  'dateString' => '2023-8',  'dateType' => 'month', 'value' => 3, 'calcTime' => '2023-11-23 0:23');
$resultData[] = (object) array('date' => '2023年9月',  'dateString' => '2023-9',  'dateType' => 'month', 'value' => 4, 'calcTime' => '2023-11-23 0:23');
$resultData[] = (object) array('date' => '2023年10月', 'dateString' => '2023-10', 'dateType' => 'month', 'value' => 5, 'calcTime' => '2023-11-23 0:23');
$resultData[] = (object) array('date' => '2023年11月', 'dateString' => '2023-11', 'dateType' => 'month', 'value' => 3, 'calcTime' => '2023-11-23 0:23');
