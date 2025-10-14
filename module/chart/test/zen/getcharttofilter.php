#!/usr/bin/env php
<?php

/**

title=测试 chartZen::getChartToFilter();
timeout=0
cid=0

- 执行chartTest模块的getChartToFilterTest方法，参数是1, 1, array 属性currentGroup @1
- 执行chartTest模块的getChartToFilterTest方法，参数是2, 999, array  @0
- 执行chartTest模块的getChartToFilterTest方法，参数是3, 2, array 属性currentGroup @3
- 执行chartTest模块的getChartToFilterTest方法，参数是4, 3, array 属性currentGroup @4
- 执行chartTest模块的getChartToFilterTest方法，参数是5, 4, array 属性currentGroup @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

$table = zenData('chart');
$table->id->range('1-10');
$table->name->range('图表1,图表2,图表3,图表4,图表5{2},图表6{3}');
$table->type->range('pie,bar,line{3},radar{4}');
$table->dataset->range('bug,task,project{8}');
$table->filters->range('[]{"field":"status","default":"active"}{"field":"module","default":"1"}{"field":"priority","default":"high"}{4}');
$table->deleted->range('0{9},1');
$table->gen(10);

su('admin');

$chartTest = new chartTest();

r($chartTest->getChartToFilterTest(1, 1, array())) && p('currentGroup') && e('1');
r($chartTest->getChartToFilterTest(2, 999, array())) && p() && e('0');
r($chartTest->getChartToFilterTest(3, 2, array('status' => 'resolved', 'priority' => 'high'))) && p('currentGroup') && e('3');
r($chartTest->getChartToFilterTest(4, 3, array())) && p('currentGroup') && e('4');
r($chartTest->getChartToFilterTest(5, 4, array('module' => '2', 'assigned' => 'admin', 'type' => 'bug'))) && p('currentGroup') && e('5');