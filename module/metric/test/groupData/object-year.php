<?php

/* 适用于按年统计的某类对象，例如 按年统计的产品发布数 */

$resultHeader = array();
$resultData   = array();

$resultHeader[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$resultHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$resultData[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'scope' => '开源', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'scope' => 'ddddw', 'value' => 3, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'scope' => '开源', 'value' => 2, 'calcTime' => '2022-11-23 0:23');
$resultData[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'scope' => 'ddddw', 'value' => 9, 'calcTime' => '2022-11-23 0:23');
$resultData[] = (object) array('date' => '2023年', 'dateString' => '2023', 'dateType' => 'year', 'scope' => '开源', 'value' => 3, 'calcTime' => '2023-11-23 0:23');
