<?php

/* 适用于按天统计的对象个数，例如 按系统统计的已关闭一级项目集数 */

$resultHeader = array();
$resultData   = array();

$resultHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$resultData[] = (object) array('value' => 2, 'calcTime' => '2021-11-23 0:01');
$resultData[] = (object) array('value' => 2, 'calcTime' => '2021-12-22 1:23');
$resultData[] = (object) array('value' => 2, 'calcTime' => '2021-12-23 1:11');
$resultData[] = (object) array('value' => 2, 'calcTime' => '2022-9-23 2:22');
$resultData[] = (object) array('value' => 2, 'calcTime' => '2022-10-23 0:01');
$resultData[] = (object) array('value' => 2, 'calcTime' => '2022-11-23 0:00');
$resultData[] = (object) array('value' => 3, 'calcTime' => '2023-10-21 0:02');
$resultData[] = (object) array('value' => 1, 'calcTime' => '2023-11-23 0:00');
