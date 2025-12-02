#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getEchartXY();
timeout=0
cid=17097

- 步骤1：2个元素的header数组 @calcTime,value

- 步骤2：3个元素的header数组 @scope,value

- 步骤3：空header数组 @0
- 步骤4：1个元素的header数组 @0
- 步骤5：4个元素的header数组 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metricTest = new metricTest();

// 测试数据：有2个元素的header数组
$header2Elements = array();
$header2Elements[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$header2Elements[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

// 测试数据：有3个元素的header数组
$header3Elements = array();
$header3Elements[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$header3Elements[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$header3Elements[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

// 测试数据：空header数组
$headerEmpty = array();

// 测试数据：有1个元素的header数组
$header1Element = array();
$header1Element[] = array('name' => 'date', 'title' => '日期', 'width' => 96);

// 测试数据：有4个元素的header数组
$header4Elements = array();
$header4Elements[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$header4Elements[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$header4Elements[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$header4Elements[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

r($metricTest->getEchartXY($header2Elements)) && p() && e('calcTime,value');     // 步骤1：2个元素的header数组
r($metricTest->getEchartXY($header3Elements)) && p() && e('scope,value');       // 步骤2：3个元素的header数组
r($metricTest->getEchartXY($headerEmpty)) && p() && e('0');                     // 步骤3：空header数组
r($metricTest->getEchartXY($header1Element)) && p() && e('0');                  // 步骤4：1个元素的header数组
r($metricTest->getEchartXY($header4Elements)) && p() && e('0');                 // 步骤5：4个元素的header数组