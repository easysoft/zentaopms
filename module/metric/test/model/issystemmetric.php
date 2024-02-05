#!/usr/bin/env php
<?php

/**

title=isSystemMetric
timeout=0
cid=1

- 测试records[1]是否是系统度量项 @1
- 测试records[2]是否是系统度量项 @0
- 测试records[3]是否是系统度量项 @0
- 测试records[4]是否是系统度量项 @0
- 测试records[5]是否是系统度量项 @0
- 测试records[6]是否是系统度量项 @1
- 测试records[7]是否是系统度量项 @1
- 测试records[8]是否是系统度量项 @0
- 测试records[9]是否是系统度量项 @0
- 测试records[10]是否是系统度量项 @1
- 测试records[11]是否是系统度量项 @1
- 测试records[12]是否是系统度量项 @1
- 测试records[13]是否是系统度量项 @1
- 测试records[14]是否是系统度量项 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$records = array();
$records[1]  = array('system' => 1);
$records[2]  = array('program' => 1);
$records[3]  = array('project' => 1);
$records[4]  = array('product' => 1);
$records[5]  = array('execution' => 1);
$records[6]  = array('code' => 1);
$records[7]  = array('pipeline' => 1);
$records[8]  = array('user' => 'admin');
$records[9]  = array('dept' => 1);
$records[10] = array('year' => 2023);
$records[11] = array('month' => 12);
$records[12] = array('week' => 52);
$records[13] = array('day' => 21);
$records[14] = array('value' => 1);

r($metric->isSystemMetric($records[1]))  && p() && e('1'); // 测试records[1]是否是系统度量项
r($metric->isSystemMetric($records[2]))  && p() && e('0'); // 测试records[2]是否是系统度量项
r($metric->isSystemMetric($records[3]))  && p() && e('0'); // 测试records[3]是否是系统度量项
r($metric->isSystemMetric($records[4]))  && p() && e('0'); // 测试records[4]是否是系统度量项
r($metric->isSystemMetric($records[5]))  && p() && e('0'); // 测试records[5]是否是系统度量项
r($metric->isSystemMetric($records[6]))  && p() && e('1'); // 测试records[6]是否是系统度量项
r($metric->isSystemMetric($records[7]))  && p() && e('1'); // 测试records[7]是否是系统度量项
r($metric->isSystemMetric($records[8]))  && p() && e('0'); // 测试records[8]是否是系统度量项
r($metric->isSystemMetric($records[9]))  && p() && e('0'); // 测试records[9]是否是系统度量项
r($metric->isSystemMetric($records[10])) && p() && e('1'); // 测试records[10]是否是系统度量项
r($metric->isSystemMetric($records[11])) && p() && e('1'); // 测试records[11]是否是系统度量项
r($metric->isSystemMetric($records[12])) && p() && e('1'); // 测试records[12]是否是系统度量项
r($metric->isSystemMetric($records[13])) && p() && e('1'); // 测试records[13]是否是系统度量项
r($metric->isSystemMetric($records[14])) && p() && e('1'); // 测试records[14]是否是系统度量项