#!/usr/bin/env php
<?php

/**

title=测试 chartZen::getChartToFilter();
timeout=0
cid=0

- 执行chartTest模块的getChartToFilterTest方法，参数是5, 1, array
 - 属性id @1
 - 属性currentGroup @5
- 执行chartTest模块的getChartToFilterTest方法，参数是5, 999, array  @0
- 执行chartTest模块的getChartToFilterTest方法，参数是0, 3, array
 - 属性id @3
 - 属性currentGroup @0
- 执行chartTest模块的getChartToFilterTest方法，参数是5, 1, array 属性id @1
- 执行chartTest模块的getChartToFilterTest方法，参数是5, 2, array 属性id @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('chart')->loadYaml('getcharttofilter', false, 2)->gen(10);

su('admin');

$chartTest = new chartZenTest();

r($chartTest->getChartToFilterTest(5, 1, array())) && p('id,currentGroup') && e('1,5');
r($chartTest->getChartToFilterTest(5, 999, array())) && p() && e('0');
r($chartTest->getChartToFilterTest(0, 3, array())) && p('id,currentGroup') && e('3,0');
r($chartTest->getChartToFilterTest(5, 1, array('status' => 'active'))) && p('id') && e('1');
r($chartTest->getChartToFilterTest(5, 2, array('status' => 'closed', 'priority' => '3'))) && p('id') && e('2');