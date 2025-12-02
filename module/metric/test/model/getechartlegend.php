#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getEchartLegend();
timeout=0
cid=17095

- 执行metricTest模块的getEchartLegendTest方法，参数是array 属性type @scroll
- 执行metricTest模块的getEchartLegendTest方法，参数是array 
 - 属性type @scroll
 - 属性selector @1
- 执行metricTest模块的getEchartLegendTest方法，参数是array 
 - 属性type @scroll
 - 属性selector @1
- 执行metricTest模块的getEchartLegendTest方法，参数是array 
 - 属性type @scroll
 - 属性selector @1
- 执行metricTest模块的getEchartLegendTest方法，参数是array 属性type @scroll

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

r($metricTest->getEchartLegendTest(array(), 'time')) && p('type') && e('scroll');
r($metricTest->getEchartLegendTest(array(array('name' => 'series1')), 'object')) && p('type,selector') && e('scroll,1');
r($metricTest->getEchartLegendTest(array(array('name' => 'series1'), array('name' => 'series2'), array('name' => 'series3')), 'object')) && p('type,selector') && e('scroll,1');
r($metricTest->getEchartLegendTest(array(), 'object')) && p('type,selector') && e('scroll,1');
r($metricTest->getEchartLegendTest(array(array('name' => 'test')), 'other')) && p('type') && e('scroll');