#!/usr/bin/env php
<?php
/**
title=getChartTypeList
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$header1   = array();
$header1[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$header1[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$header2   = array();
$header2[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$header2[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$header2[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$header2[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$header3   = array();
$header3[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$header3[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$header3[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$header4   = array();

r(count($metric->getChartTypeList($header1))) && p() && e(4); // 获取header1的chartType数量
r(count($metric->getChartTypeList($header2))) && p() && e(3); // 获取header2的chartType数量
r(count($metric->getChartTypeList($header3))) && p() && e(3); // 获取header3的chartType数量
r(count($metric->getChartTypeList($header4))) && p() && e(4); // 获取header3的chartType数量
