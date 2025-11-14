#!/usr/bin/env php
<?php

/**

title=测试 metricModel::buildSearchForm();
timeout=0
cid=17063

- 执行metricTest模块的buildSearchFormTest方法，参数是1, '/metric/browse'  @1
- 执行metricTest模块的buildSearchFormTest方法，参数是0, '/test/url'  @1
- 执行metricTest模块的buildSearchFormTest方法，参数是5, ''  @1
- 执行metricTest模块的buildSearchFormTest方法，参数是-1, '/negative/test'  @1
- 执行metricTest模块的buildSearchFormTest方法，参数是999, '/special?param=value&test=1'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

r($metricTest->buildSearchFormTest(1, '/metric/browse')) && p() && e('1');
r($metricTest->buildSearchFormTest(0, '/test/url')) && p() && e('1');
r($metricTest->buildSearchFormTest(5, '')) && p() && e('1');
r($metricTest->buildSearchFormTest(-1, '/negative/test')) && p() && e('1');
r($metricTest->buildSearchFormTest(999, '/special?param=value&test=1')) && p() && e('1');