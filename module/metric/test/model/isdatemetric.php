#!/usr/bin/env php
<?php

/**

title=测试 metricModel::isDateMetric();
timeout=0
cid=17138

- 执行metric模块的isDateMetric方法，参数是$systemHeader  @false
- 执行metric模块的isDateMetric方法，参数是$fullHeader  @true
- 执行metric模块的isDateMetric方法，参数是$scopeOnlyHeader  @false
- 执行metric模块的isDateMetric方法，参数是$emptyHeader  @false
- 执行metric模块的isDateMetric方法，参数是$dateOnlyHeader  @true
- 执行metric模块的isDateMetric方法，参数是$dateFirstHeader  @true
- 执行metric模块的isDateMetric方法，参数是$similarNameHeader  @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

su('admin');

$metric = new metricTest();

// 测试数据准备
$systemHeader = array();
$systemHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$systemHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$fullHeader = array();
$fullHeader[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$fullHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$fullHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$fullHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$scopeOnlyHeader = array();
$scopeOnlyHeader[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$scopeOnlyHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$scopeOnlyHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$emptyHeader = array();

$dateOnlyHeader = array();
$dateOnlyHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);

$dateFirstHeader = array();
$dateFirstHeader[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$dateFirstHeader[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$dateFirstHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);

$similarNameHeader = array();
$similarNameHeader[] = array('name' => 'dateTime', 'title' => '时间', 'width' => 96);
$similarNameHeader[] = array('name' => 'endDate', 'title' => '结束日期', 'width' => 96);
$similarNameHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);

r($metric->isDateMetric($systemHeader)) && p('') && e('false');
r($metric->isDateMetric($fullHeader)) && p('') && e('true');
r($metric->isDateMetric($scopeOnlyHeader)) && p('') && e('false');
r($metric->isDateMetric($emptyHeader)) && p('') && e('false');
r($metric->isDateMetric($dateOnlyHeader)) && p('') && e('true');
r($metric->isDateMetric($dateFirstHeader)) && p('') && e('true');
r($metric->isDateMetric($similarNameHeader)) && p('') && e('false');