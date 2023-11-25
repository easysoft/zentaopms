<?php

/* 适用于按年统计的对象个数，例如 按系统统计的年度新增一级项目集数 */

$resultHeader = array();
$resultData   = array();

$resultHeader[] = array('name' => 'scope', 'title' => '项目名称', 'width' => 96);
$resultHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$resultHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$resultData[] = (object) array('scope' => '企业管理系统', 'scopeID' => 7, 'value' => 739245, 'calcTime' => '2023-11-24 9:20');
$resultData[] = (object) array('scope' => '开源', 'scopeID' => 8, 'value' => 1992, 'calcTime' => '2023-11-24 9:20');
$resultData[] = (object) array('scope' => '项目型项目', 'scopeID' => 9, 'value' => 19684, 'calcTime' => '2023-11-24 9:20');

$resultData[] = (object) array('scope' => '企业管理系统', 'scopeID' => 7, 'value' => 739225, 'calcTime' => '2023-11-23 9:20');
$resultData[] = (object) array('scope' => '开源', 'scopeID' => 8, 'value' => 1993, 'calcTime' => '2023-11-23 9:20');

$resultData[] = (object) array('scope' => '企业管理系统', 'scopeID' => 7, 'value' => 7325, 'calcTime' => '2022-11-23 9:20');
$resultData[] = (object) array('scope' => '开源', 'scopeID' => 8, 'value' => 3, 'calcTime' => '2022-11-23 9:20');
