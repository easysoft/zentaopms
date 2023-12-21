#!/usr/bin/env php
<?php
/**
title=isObjectMetric
timeout=0
cid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

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

r($metric->isObjectMetric($header1)) && p('') && e('false'); // 测试系统度量项
r($metric->isObjectMetric($header2)) && p('') && e('true');  // 测试有对象的度量项
r($metric->isObjectMetric($header3)) && p('') && e('true');  // 测试另一个有对象的度量项
r($metric->isObjectMetric($header4)) && p('') && e('false'); // 测试传入空值
