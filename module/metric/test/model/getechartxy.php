#!/usr/bin/env php
<?php

/**

title=getEchartXY
timeout=0
cid=1

- 执行metric模块的getEchartXY方法，参数是$header1  @calcTime,value

- 执行metric模块的getEchartXY方法，参数是$header2  @0
- 执行metric模块的getEchartXY方法，参数是$header3  @scope,value

- 执行metric模块的getEchartXY方法，参数是$header4  @0

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

r($metric->getEchartXY($header1)) && p() && e('calcTime,value');
r($metric->getEchartXY($header2)) && p() && e('0');
r($metric->getEchartXY($header3)) && p() && e('scope,value');
r($metric->getEchartXY($header4)) && p() && e('0');