#!/usr/bin/env php
<?php

/**

title=测试 screenModel::processMetricFilter();
timeout=0
cid=18278

- 执行screenTest模块的processMetricFilterTest方法，参数是array 属性dateBegin @2021-01-01
- 执行screenTest模块的processMetricFilterTest方法，参数是array 属性scope @1
- 执行screenTest模块的processMetricFilterTest方法，参数是array  @0
- 执行screenTest模块的processMetricFilterTest方法，参数是array 属性dateBegin @2021-01
- 执行screenTest模块的processMetricFilterTest方法，参数是array 属性dateBegin @2021-53

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();

r($screenTest->processMetricFilterTest(array(array('field' => 'date', 'default' => array(1609459200000, 1640995200000))), 'day')) && p('dateBegin') && e('2021-01-01');
r($screenTest->processMetricFilterTest(array(array('field' => 'product', 'default' => array(1))), 'day')) && p('scope') && e('1');
r($screenTest->processMetricFilterTest(array(array('field' => 'status', 'default' => '')), 'day')) && p() && e('0');
r($screenTest->processMetricFilterTest(array(array('field' => 'date', 'default' => array(1609459200000, 1640995200000)), array('field' => 'product', 'default' => array(1, 2))), 'month')) && p('dateBegin') && e('2021-01');
r($screenTest->processMetricFilterTest(array(array('field' => 'date', 'default' => array(1609459200000, 1640995200000))), 'week')) && p('dateBegin') && e('2021-53');