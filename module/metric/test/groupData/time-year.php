<?php

/* 适用于按年统计的对象个数，例如 按系统统计的年度新增一级项目集数 */

$resultHeader = array();
$resultData   = array();

$resultHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$resultHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$resultData[] = (object) array('date' => '2021年', 'dateString' => '2021', 'dateType' => 'year', 'value' => 1, 'calcTime' => '2021-11-23 0:23');
$resultData[] = (object) array('date' => '2022年', 'dateString' => '2022', 'dateType' => 'year', 'value' => 2, 'calcTime' => '2022-11-23 0:23');
$resultData[] = (object) array('date' => '2023年', 'dateString' => '2023', 'dateType' => 'year', 'value' => 3, 'calcTime' => '2023-11-23 0:23');
