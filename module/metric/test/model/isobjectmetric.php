#!/usr/bin/env php
<?php
/**
title=isObjectMetric
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$records = array();

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

r($metric->isObjectMetric($header1))  && p() && e('0'); // 测试header1是否有对象概念
r($metric->isObjectMetric($header2))  && p() && e('1'); // 测试header2是否有对象概念
r($metric->isObjectMetric($header3))  && p() && e('1'); // 测试header3是否有对象概念
r($metric->isObjectMetric($header4))  && p() && e('0'); // 测试header4是否有对象概念
