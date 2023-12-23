#!/usr/bin/env php
<?php
/**
title=isOldMetric
cid=1
pid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$metricList = array();
$metricList[0] = array('code' => 'test1', 'type' => 'sql');
$metricList[1] = array('code' => 'test2', 'type' => 'php');
$metricList[2] = array('code' => 'test3', 'type' => 'wrong value');
$metricList[3] = array('code' => 'test4');
$metricList[4] = array();

r($metric->isOldMetric((object)$metricList[0])) && p('') && e('1'); // 测试度量项0是否是旧版度量项
r($metric->isOldMetric((object)$metricList[1])) && p('') && e('0'); // 测试度量项1是否是旧版度量项
r($metric->isOldMetric((object)$metricList[2])) && p('') && e('0'); // 测试度量项2是否是旧版度量项
r($metric->isOldMetric((object)$metricList[3])) && p('') && e('0'); // 测试度量项3是否是旧版度量项
r($metric->isOldMetric((object)$metricList[4])) && p('') && e('0'); // 测试度量项4是否是旧版度量项
