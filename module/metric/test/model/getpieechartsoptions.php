#!/usr/bin/env php
<?php
/**
title=getPieEchartsOptions
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

$data1 = array();
$data1[] = (object)array('calcTime' => '2023-1-1', 'value' => '1');
$data1[] = (object)array('calcTime' => '2023-1-2', 'value' => '2');
$data1[] = (object)array('calcTime' => '2023-1-3', 'value' => '3');

$data2 = array();
$data2[] = (object)array('scope' => 'project', 'value' => '1');
$data2[] = (object)array('scope' => 'project', 'value' => '2');
$data2[] = (object)array('scope' => 'project', 'value' => '3');

$data3 = array();
$data2[] = (object)array('scope' => 'product', 'value' => '1');
$data2[] = (object)array('scope' => 'product', 'value' => '2');
$data2[] = (object)array('scope' => 'product', 'value' => '3');

r($metric->getPieEchartsOptions($header1, $data1)) && p('legend:orient,left,type') && e('vertical,left,scroll'); // 测试数据集1的legend
r($metric->getPieEchartsOptions($header2, $data2)) && p('legend:orient,left,type') && e('vertical,left,scroll'); // 测试数据集2的legend
r($metric->getPieEchartsOptions($header3, $data3)) && p('legend:orient,left,type') && e('vertical,left,scroll'); // 测试数据集3的legend
r($metric->getPieEchartsOptions($header4, $data3)) && p('legend:orient,left,type') && e('vertical,left,scroll'); // 测试数据集4的legend

r($metric->getPieEchartsOptions($header1, $data1, 'series')) && p('0:type') && e('pie'); // 测试数据集1的series
r($metric->getPieEchartsOptions($header2, $data2, 'series')) && p('0:type') && e('pie'); // 测试数据集2的series
r($metric->getPieEchartsOptions($header3, $data3, 'series')) && p('0:type') && e('pie'); // 测试数据集3的series
r($metric->getPieEchartsOptions($header4, $data3, 'series')) && p('0:type') && e('pie'); // 测试数据集4的series

r($metric->getPieEchartsOptions($header1, $data1, 'data')) && p('0:name,value') && e('2023-1-1,1'); // 测试数据集1的series的第1条data
r($metric->getPieEchartsOptions($header1, $data1, 'data')) && p('1:name,value') && e('2023-1-2,2'); // 测试数据集1的series的第2条data
r($metric->getPieEchartsOptions($header1, $data1, 'data')) && p('2:name,value') && e('2023-1-3,3'); // 测试数据集1的series的第3条data
r($metric->getPieEchartsOptions($header3, $data3, 'data')) && p('') && e('0');                      // 测试数据集3的series.data
r($metric->getPieEchartsOptions($header4, $data3, 'data')) && p('') && e('0');                      // 测试数据集4的series.data
